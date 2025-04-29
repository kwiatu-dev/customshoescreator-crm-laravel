<?php

namespace App\Http\Controllers;

use App\Helpers\RequestProcessor;
use App\Models\User;
use App\Models\UserEvents;
use App\Models\UserEventType;
use App\Notifications\UserEvent\UserEventCreateNotification;
use App\Notifications\UserEvent\UserEventDeleteNotification;
use App\Notifications\UserEvent\UserEventRestoreNotification;
use App\Notifications\UserEvent\UserEventUpdateNotification;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserEventsController extends Controller
{
    private $fields;

    public function __construct(
        private NotificationService $notificationService,
    )
    {
        $this->middleware(['auth', 'verified']);

        $this->fields = [
            'title',
            'remarks',
            'start',
            'end',
            'user_id',
            'type_id',
        ];
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields = RequestProcessor::validation($request, $this->fields, new UserEvents(), [
            'user_id' => 'required|exists:users,id',
            'type_id' => 'required|exists:user_event_types,id',
        ]);

        $user = User::findOrFail($fields['created_by_user_id'] ?? Auth::id());

        if (!$user->is_admin && $fields['user_id'] !== $user->id) {
            return redirect()->back()->withErrors(['user_id' => 'Nie masz uprawnień do tworzenia wydarzeń dla innych użytkowników.']);
        }

        $userEvent = UserEvents::create([
            ...$fields,
            'created_by_user_id' => $user->id,
        ]);

        $this->notificationService->sendNotification(
            new UserEventCreateNotification($userEvent, $request->user(), $userEvent->user),
        );

        if(request()->route()->getName() == 'user-events.create'){
            return redirect()->route('user-events.index')->with('success', 'Wydarzenie zostało dodane!');
        }

        return back()->with('inertia', $userEvent);
    }

    /**
     * Display the specified resource.
     */
    public function show(UserEvents $userEvent)
    {
        $this->authorize('view', $userEvent);

        $userEvent->append([
            'editable',
            'deletable',
            'restorable',
        ]);

        $userEvent->load([
            'type',
            'user' => function ($query) {
                $query->withTrashed();
            },
        ]);
            
        return inertia('UserEvents/Show', [
            'userEvent' => $userEvent,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserEvents $userEvent)
    {
        $this->authorize('update', $userEvent);

        return inertia('UserEvents/Edit', [
            'userEvent' => $userEvent->load([
                'type',
                'user' => function ($query) {
                    $query->withTrashed();
                },
            ]),
            'users' => User::query()->get(),
            'types' => UserEventType::query()->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UserEvents $userEvent)
    {
        $this->authorize('update', $userEvent);

        $fields = RequestProcessor::validation($request, $this->fields, new UserEvents(), [
            'start' => 'required|date|date_format:Y-m-d',
            'user_id' => 'required|exists:users,id',
            'type_id' => 'required|exists:user_event_types,id',
        ]);

        $user = User::findOrFail($fields['created_by_user_id'] ?? Auth::id());

        if (!$user->is_admin && $fields['user_id'] !== $user->id) {
            return redirect()->back()->withErrors(['user_id' => 'Nie masz uprawnień do tworzenia wydarzeń dla innych użytkowników.']);
        }

        $userEvent->update($fields);

        $this->notificationService->sendNotification(
            new UserEventUpdateNotification($userEvent, $request->user(), $userEvent->user),
        );

        return redirect()->route('restore.state', ['url' => route('organizer.index')])->with('success', 'Wydarzenie zostało zaktualizowane!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserEvents $userEvent, Request $request)
    {
        $this->authorize('delete', $userEvent);

        if (!$userEvent->deletable) {
            return redirect()->back()->with('failed', 'Nie można usunać tej inwestycji!');
        }

        $userEvent->deleteOrFail();

        $this->notificationService->sendNotification(
            new UserEventDeleteNotification($userEvent, $request->user(), $userEvent->user),
        );

        return redirect()->back()->with('success', 'Wydarzenie zostało usunięte!');
    }

    public function restore(UserEvents $userEvent, Request $request){
        $this->authorize('restore', $userEvent);

        if (!$userEvent->restorable) {
            return redirect()->back()->with('failed', 'Nie można przywrócić tego wydarzenia!');
        }

        $userEvent->restore();

        $this->notificationService->sendNotification(
            new UserEventRestoreNotification($userEvent, $request->user(), $userEvent->user),
        );

        return redirect()->back()->with('success', 'Wydarzenie zostało przywrócone!');
    }
}
