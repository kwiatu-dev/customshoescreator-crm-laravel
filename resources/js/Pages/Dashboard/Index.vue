<template>
  <div class="grid grid-cols-12 gap-2">
    <div class="hidden md:flex col-span-4 font-medium flex-row flex-nowrap items-center justify-between border border-solid border-gray-100 dark:border-gray-500 rounded-md p-4 shadow-sm text-xl">
      Projekty
    </div>

    <Link 
      :href="route('projects.index', { status_id: '1'})"
      class="col-span-6 md:col-span-2 card text-center" 
      style="background-color: rgb(253 224 71 / .5) !important"
    >
      <font-awesome-icon :icon="['fas', 'pause']" class="!text-yellow-400 text-xl" />
      <h2 class="!text-gray-600 dark:!text-gray-300">Oczekujące</h2>
      <div class="font-bold text-2xl">10</div>
    </Link>

    <Link
      :href="route('projects.index', { status_id: '2'})"
      class="col-span-6 md:col-span-2 card text-center" 
      style="background-color: rgb(251 146 60 / .5) !important"
    >
      <font-awesome-icon :icon="['fas', 'screwdriver-wrench']" class="!text-orange-400 text-xl" />
      <h2 class="!text-gray-600 dark:!text-gray-300">W trakcie</h2>
      <div class="font-bold text-2xl">10</div>
    </Link>

    <Link 
      :href="route('projects.index', { after_deadline: 'true'})"
      class="col-span-6 md:col-span-2 card text-center" 
      style="background-color: rgb(248 113 113 / .5) !important;"
    >
      <font-awesome-icon :icon="['fas', 'triangle-exclamation']" class="!text-red-400 text-xl" />
      <h2 class="!text-gray-600 dark:!text-gray-300">Po czasie</h2>
      <div class="font-bold text-2xl">10</div>
    </Link>

    <Link 
      :href="route('projects.index', { status_id: '3'})"
      class="col-span-6 md:col-span-2 card text-center " 
      style="background-color: rgb(74 222 128 / .5) !important;"
    >
      <font-awesome-icon :icon="['fas', 'flag-checkered']" class="!text-green-400 text-xl" />
      <h2 class="!text-gray-600 dark:!text-gray-300">Zakończone</h2>
      <div class="font-bold text-2xl ">10</div>
    </Link>

    <div class="col-span-12 font-medium text-gray-600 mt-4 flex flex-row flex-nowrap items-center justify-between">
      <div>Całkowite wartości</div>
    </div>

    <div class="col-span-12 md:col-span-4 card">
      <h2>Stan konta</h2>
      <div class="flex flex-row flex-nowrap items-center gap-4">
        <font-awesome-icon :icon="['fas', 'wallet']" style="font-size: 40px;" class="text-indigo-400 dark:text-indigo-500" />
        <div class="text-gray-800 font-medium text-2xl">12137.12 <span>zł</span></div>
      </div>
    </div>
    
    <div class="hidden md:block md:col-span-8" />

    <div class="col-span-6 md:col-span-4 card">
      <h2>Użytkownicy</h2>
      <div class="total">10</div>
    </div>
    <div class="col-span-6 md:col-span-4 card">
      <h2>Klienci</h2>
      <div class="total">23</div>
    </div>
    <div class="col-span-6 md:col-span-4 card">
      <h2>Projekty</h2>
      <div class="total">56</div>
    </div>
    <div class="col-span-6 md:col-span-4 card">
      <h2>Przychód</h2>
      <div class="total">
        1215 <span>zł</span> 
      </div>
    </div>
    <div class="col-span-6 md:col-span-4 card">
      <h2>Wydatki</h2>
      <div class="total">
        230 <span>zł</span> 
      </div>
    </div>
    <div class="col-span-6 md:col-span-4 card">
      <h2>Dochód</h2>
      <div class="total">
        7612 <span>zł</span> 
      </div>
    </div>

    <div class="col-span-12 font-medium text-gray-600 mt-4 flex flex-row flex-wrap items-center justify-between">
      <div>Podsumowanie KPI</div>
      <DateRangePicker v-model="dateRange" class="ml-auto mt-4 md:ml-0 md:mt-0" />
    </div>

    <div class="col-span-12 md:col-span-4 card">
      <div class="flex flex-row flex-nowrap justify-start items-center gap-4 mb-4">
        <div class="p-2 bg-indigo-400 dark:bg-indigo-500 w-16 h-16 rounded-xl flex items-center justify-center">
          <font-awesome-icon :icon="['fas', 'sack-dollar']" class="text-gray-50 dark:text-gray-200" style="font-size: 30px;" />
        </div>
        <div class="text-gray-800 font-medium text-xl dark:text-gray-400">Pieniądze</div>
      </div>
      <div class="flex md:flex-col md:gap-2 gap-8 flex-wrap">
        <div>
          <div class="text-gray-400 dark:text-gray-500">Przychód</div>
          <div class="font-medium text-gray-600 dark:text-gray-400 text-xl flex flex-row flex-nowrap justify-start md:justify-between items-center gap-4">
            <span>10 zł</span>
            <span class="text-xs text-green-500">
              <font-awesome-icon :icon="['fas', 'caret-up']" />
              2%
            </span>
          </div>
        </div>
        <div>
          <div class="text-gray-400 dark:text-gray-500">Wydatki</div>
          <div class="font-medium text-gray-600 dark:text-gray-400 text-xl flex flex-row flex-nowrap justify-start md:justify-between items-center gap-4">
            <span>10 zł</span>
            <span class="text-xs text-green-500">
              <font-awesome-icon :icon="['fas', 'caret-up']" />
              2%
            </span>
          </div>
        </div>
        <div>
          <div class="text-gray-400 dark:text-gray-500">Dochód</div>
          <div class="font-medium text-gray-600 dark:text-gray-400 text-xl flex flex-row flex-nowrap justify-start md:justify-between items-center gap-4">
            <span>10 zł</span>
            <span class="text-xs text-green-500">
              <font-awesome-icon :icon="['fas', 'caret-up']" />
              2%
            </span>
          </div>
        </div>
      </div>
    </div>

    
    <div class="col-span-12 md:col-span-4 card">
      <div class="flex flex-row flex-nowrap justify-start items-center gap-4 mb-4">
        <div class="p-2 bg-indigo-400 dark:bg-indigo-500 w-16 h-16 rounded-xl flex items-center justify-center">
          <font-awesome-icon :icon="['fas', 'pen-ruler']" class="text-gray-50 dark:text-gray-200" style="font-size: 30px;" />
        </div>
        <div class="text-gray-800 dark:text-gray-400 font-medium text-xl">Projekty</div>
      </div>
      <div class="flex md:flex-col md:gap-2 gap-8 flex-wrap">
        <div>
          <div class="text-gray-400 dark:text-gray-500">Nowe</div>
          <div class="font-medium text-gray-600 dark:text-gray-400 text-xl flex flex-row flex-nowrap justify-start md:justify-between items-center gap-4">
            <span>10</span>
            <span class="text-xs text-green-500">
              <font-awesome-icon :icon="['fas', 'caret-up']" />
              2%
            </span>
          </div>
        </div>
        <div>
          <div class="text-gray-400 dark:text-gray-500">Zakończone</div>
          <div class="font-medium text-gray-600 dark:text-gray-400 text-xl flex flex-row flex-nowrap justify-start md:justify-between items-center gap-4">
            <span>10</span>
            <span class="text-xs text-green-500">
              <font-awesome-icon :icon="['fas', 'caret-up']" />
              2%
            </span>
          </div>
        </div>
        <div>
          <div class="text-gray-400 dark:text-gray-500">Średni czas realizacji</div>
          <div class="font-medium text-gray-600 dark:text-gray-400 text-xl flex flex-row flex-nowrap justify-start md:justify-between items-center gap-4">
            <span>10</span>
            <span class="text-xs text-green-500">
              <font-awesome-icon :icon="['fas', 'caret-up']" />
              2%
            </span>
          </div>
        </div>
      </div>
    </div>

    <div class="col-span-12 md:col-span-4 card">
      <div class="flex flex-row flex-nowrap justify-start items-center gap-4 mb-4">
        <div class="p-2 bg-indigo-400 dark:bg-indigo-500 w-16 h-16 rounded-xl flex items-center justify-center">
          <font-awesome-icon :icon="['fas', 'people-group']" class="text-gray-50 dark:text-gray-200" style="font-size: 30px;" />
        </div>
        <div class="text-gray-800 dark:text-gray-400 font-medium text-xl">Klienci</div>
      </div>
      <div class="flex md:flex-col md:gap-2 gap-8 flex-wrap">
        <div>
          <div class="text-gray-400 dark:text-gray-500">Nowi</div>
          <div class="font-medium text-gray-600 dark:text-gray-400 text-xl flex flex-row flex-nowrap justify-start md:justify-between items-center gap-4">
            <span>10</span>
            <span class="text-xs text-green-500">
              <font-awesome-icon :icon="['fas', 'caret-up']" />
              2%
            </span>
          </div>
        </div>
        <div>
          <div class="text-gray-400 dark:text-gray-500">Powracający</div>
          <div class="font-medium text-gray-600 dark:text-gray-400 text-xl flex flex-row flex-nowrap justify-start md:justify-between items-center gap-4">
            <span>10</span>
            <span class="text-xs text-green-500">
              <font-awesome-icon :icon="['fas', 'caret-up']" />
              2%
            </span>
          </div>
        </div>
      </div>
    </div>
    
    <div class="col-span-6 sm:col-span-4 md:col-span-2 card">
      <div class="text-gray-800 dark:text-gray-500 font-medium text-xs">
        <div>Renowacja butów</div>
        <div class="font-medium text-gray-600 dark:text-gray-400 text-xl flex flex-row flex-nowrap justify-between items-center">
          <span>10</span>
          <span class="text-xs text-green-500">
            <font-awesome-icon :icon="['fas', 'caret-up']" />
            2%
          </span>
        </div>
      </div>
    </div>
    <div class="col-span-6 sm:col-span-4 md:col-span-2 card">
      <div class="text-gray-800 dark:text-gray-500 font-medium text-xs">
        <div>Personalizacja butów</div>
        <div class="font-medium text-gray-600 dark:text-gray-400 text-xl flex flex-row flex-nowrap justify-between items-center">
          <span>10</span>
          <span class="text-xs text-green-500">
            <font-awesome-icon :icon="['fas', 'caret-up']" />
            2%
          </span>
        </div>
      </div>
    </div>
    <div class="col-span-6 sm:col-span-4 md:col-span-2 card">
      <div class="text-gray-800 dark:text-gray-500 font-medium text-xs">
        <div>Personalizacja ubrań</div>
        <div class="font-medium text-gray-600 dark:text-gray-400 text-xl flex flex-row flex-nowrap justify-between items-center">
          <span>10</span>
          <span class="text-xs text-green-500">
            <font-awesome-icon :icon="['fas', 'caret-up']" />
            2%
          </span>
        </div>
      </div>
    </div>
    <div class="col-span-6 sm:col-span-4 md:col-span-2 card">
      <div class="text-gray-800 dark:text-gray-500 font-medium text-xs">
        <div>Haft ręczny</div>
        <div class="font-medium text-gray-600 dark:text-gray-400 text-xl flex flex-row flex-nowrap justify-between items-center">
          <span>10</span>
          <span class="text-xs text-green-500">
            <font-awesome-icon :icon="['fas', 'caret-up']" />
            2%
          </span>
        </div>
      </div>
    </div>
    <div class="col-span-6 sm:col-span-4 md:col-span-2 card">
      <div class="text-gray-800 dark:text-gray-500 font-medium text-xs">
        <div>Haft komputerowy</div>
        <div class="font-medium text-gray-600 dark:text-gray-400 text-xl flex flex-row flex-nowrap justify-between items-center">
          <span>10</span>
          <span class="text-xs text-green-500">
            <font-awesome-icon :icon="['fas', 'caret-up']" />
            2%
          </span>
        </div>
      </div>
    </div>
    <div class="col-span-6 sm:col-span-4 md:col-span-2 card">
      <div class="text-gray-800 dark:text-gray-500 font-medium text-xs">
        <div>Inne</div>
        <div class="font-medium text-gray-600 dark:text-gray-400 text-xl flex flex-row flex-nowrap justify-between items-center">
          <span>10</span>
          <span class="text-xs text-green-500">
            <font-awesome-icon :icon="['fas', 'caret-up']" />
            2%
          </span>
        </div>
      </div>
    </div>

    <div class="col-span-12 font-medium text-gray-600 mt-4 flex flex-row flex-nowrap items-center justify-between">
      <div>TOP 3</div>
    </div>

    <div class="col-span-12 md:col-span-6 card flex flex-col justify-between">
      <ViewToggle v-model="top3UsersSelectedView" :options="['Ostatni miesiąc', 'Od zawsze']" />
      <div class="flex flex-col flex-nowrap justify-center items-center my-4">
        <h2 class="!text-xl !text-gray-700 dark:!text-gray-500">Użytkownicy</h2>
        <p class="text-center -mt-2 text-sm text-gray-400">Wygenerowany przychód</p>
      </div>
      <div class="grid grid-cols-12 gap-2 mt-8" style="min-height: 356px;">
        <div class="col-span-4 flex flex-col items-center justify-end">
          <div class="mb-4 flex flex-col flex-nowrap justify-center items-center w-full">
            <div>
              <font-awesome-icon :icon="['fas', 'user']" class="p-3 bg-gray-300 dark:bg-gray-400 rounded-full shadow-sm text-white" style="font-size: 20px;" />
            </div>
            <div class="text-sm text-gray-900 dark:text-gray-500 font-medium">Filip</div>
          </div>
          <div 
            style="height: 150px;"
            class="bg-green-200 dark:bg-green-700 rounded-md shadow-sm w-full flex flex-row justify-center items-center relative text-white font-medium text-xl"
          >
            <font-awesome-icon :icon="['fas', '2']" />
            <div class="absolute top-2 left-2 w-1 h-1 bg-green-400 rounded-full" />
            <div class="absolute top-2 right-2 w-1 h-1 bg-green-400 rounded-full" />
            <div class="absolute bottom-2 left-2 w-1 h-1 bg-green-400 rounded-full" />
            <div class="absolute bottom-2 right-2 w-1 h-1 bg-green-400 rounded-full" />
          </div>
        </div>
        <div class="col-span-4 flex flex-col items-center justify-end">
          <div class="mb-4 flex flex-col flex-nowrap justify-center items-center w-full">
            <div>
              <font-awesome-icon :icon="['fas', 'user']" class="p-3 bg-gray-300 dark:bg-gray-400  rounded-full shadow-sm text-white" style="font-size: 20px;" />
            </div>
            <div class="text-sm text-gray-900 dark:text-gray-500 font-medium">Aleksandra</div>
          </div>
          <div 
            style="height: 200px;"
            class="bg-rose-200 dark:bg-rose-700 rounded-md shadow-sm w-full flex flex-row justify-center items-center relative text-white font-medium text-xl"
          >
            <font-awesome-icon :icon="['fas', 'trophy']" />
            <div class="absolute top-2 left-2 w-1 h-1 bg-rose-400 rounded-full" />
            <div class="absolute top-2 right-2 w-1 h-1 bg-rose-400 rounded-full" />
            <div class="absolute bottom-2 left-2 w-1 h-1 bg-rose-400 rounded-full" />
            <div class="absolute bottom-2 right-2 w-1 h-1 bg-rose-400 rounded-full" />
          </div>
        </div>
        <div class="col-span-4 flex flex-col items-center justify-end">
          <div class="mb-4 flex flex-col flex-nowrap justify-center items-center w-full">
            <div>
              <font-awesome-icon :icon="['fas', 'user']" class="p-3 bg-gray-300 dark:bg-gray-400 rounded-full shadow-sm text-white" style="font-size: 20px;" />
            </div>
            <div class="text-sm text-gray-900 dark:text-gray-500 font-medium">Weronika</div>
          </div>
          <div 
            style="height: 100px;"
            class="bg-sky-200 dark:bg-sky-700 rounded-md shadow-sm w-full flex flex-row justify-center items-center relative text-white font-medium text-xl"
          >
            <font-awesome-icon :icon="['fas', '3']" />
            <div class="absolute top-2 left-2 w-1 h-1 bg-sky-400 rounded-full" />
            <div class="absolute top-2 right-2 w-1 h-1 bg-sky-400 rounded-full" />
            <div class="absolute bottom-2 left-2 w-1 h-1 bg-sky-400 rounded-full" />
            <div class="absolute bottom-2 right-2 w-1 h-1 bg-sky-400 rounded-full" />
          </div>
        </div>
      </div>
      <div>
        <div class="mt-4 flex flex-row flex-wrap justify-between items-center gap-4">
          <div class="flex flex-row flex-nowrap justify-start items-center gap-2 w-full">
            <div class="p-2 bg-rose-300 dark:bg-rose-800 rounded-full h-8 w-8 flex flex-row justify-center items-center">
              <font-awesome-icon :icon="['fas', 'trophy']" style="font-size: 16px;" class="font-medium text-white" />
            </div>
            <div class="text-gray-500 dark:text-gray-400 text-md font-medium">Aleksandra</div>
          </div>
          <table class="text-left border-collapse md:w-3/4 w-full">
            <thead>
              <tr class="bg-gray-100 text-gray-600 text-sm font-medium">
                <th class="py-2 px-4 border-b">Przychód</th>
                <th class="py-2 px-4 border-b">Zakończone projekty</th>
                <th class="py-2 px-4 border-b">Średni czas realizacji</th>
              </tr>
            </thead>
            <tbody>
              <tr class="text-gray-700 text-sm">
                <td class="py-2 px-4 border-b">10</td>
                <td class="py-2 px-4 border-b">10</td>
                <td class="py-2 px-4 border-b">10</td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="w-full bg-gray-200 dark:bg-gray-700 my-4" style="height: 1px;" />
        <div class="mt-4 flex flex-row flex-wrap justify-between items-center gap-4">
          <div class="flex flex-row flex-nowrap justify-start items-center gap-2 w-full">
            <div class="p-2 bg-green-300 dark:bg-green-800 rounded-full h-8 w-8 flex flex-row justify-center items-center">
              <font-awesome-icon :icon="['fas', '2']" style="font-size: 16px;" class="font-medium text-white" />
            </div>
            <div class="text-gray-500 dark:text-gray-400 text-md font-medium">Filip</div>
          </div>
          <table class="text-left border-collapse md:w-3/4 w-full">
            <thead>
              <tr class="bg-gray-100 text-gray-600 text-sm font-medium">
                <th class="py-2 px-4 border-b">Przychód</th>
                <th class="py-2 px-4 border-b">Zakończone projekty</th>
                <th class="py-2 px-4 border-b">Średni czas realizacji</th>
              </tr>
            </thead>
            <tbody>
              <tr class="text-gray-700 text-sm">
                <td class="py-2 px-4 border-b">10</td>
                <td class="py-2 px-4 border-b">10</td>
                <td class="py-2 px-4 border-b">10</td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="w-full bg-gray-200 dark:bg-gray-700 my-4" style="height: 1px;" />
        <div class="mt-4 flex flex-row flex-wrap justify-between items-center gap-4">
          <div class="flex flex-row flex-nowrap justify-start items-center gap-2 w-full">
            <div class="p-2 bg-sky-300 dark:bg-sky-800 rounded-full h-8 w-8 flex flex-row justify-center items-center">
              <font-awesome-icon :icon="['fas', '3']" style="font-size: 16px;" class="font-medium text-white" />
            </div>
            <div class="text-gray-500 dark:text-gray-400 text-md font-medium">Weronika</div>
          </div>
          <table class="text-left border-collapse md:w-3/4 w-full">
            <thead>
              <tr class="bg-gray-100 text-gray-600 text-sm font-medium">
                <th class="py-2 px-4 border-b">Przychód</th>
                <th class="py-2 px-4 border-b">Zakończone projekty</th>
                <th class="py-2 px-4 border-b">Średni czas realizacji</th>
              </tr>
            </thead>
            <tbody>
              <tr class="text-gray-700 text-sm">
                <td class="py-2 px-4 border-b">10</td>
                <td class="py-2 px-4 border-b">10</td>
                <td class="py-2 px-4 border-b">10</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <div class="col-span-12 md:col-span-6 card flex flex-col justify-between">
      <ViewToggle v-model="top3ProjectSelectedView" :options="['Ostatni miesiąc', 'Od zawsze']" />
      <div class="flex flex-col flex-nowrap justify-center items-center my-4">
        <h2 class="!text-xl !text-gray-700 dark:!text-gray-500">Projekty</h2>
        <p class="text-center -mt-2 text-sm text-gray-400">Wygenerowany przychód</p>
      </div>
      <div class="grid grid-cols-12 gap-2 mt-8">
        <div class="col-span-4 flex flex-col items-center justify-end">
          <div class="mb-4 flex flex-col flex-nowrap justify-center items-center w-full">
            <div>
              <img
                src="https://customshoescreator.pl/wp-content/uploads/2022/04/malowanie-butow-na-zamowienie-custom-shoes-creator.webp" 
                alt="" 
                class="p-1 bg-gray-300 dark:bg-gray-600 rounded-full shadow-sm text-white top-3-img top-3-img-level-2"
              />
            </div>
            <div class="text-sm text-gray-900 dark:text-gray-500 font-medium">Projekt #11</div>
          </div>
          <div 
            style="height: 150px;"
            class="bg-green-200 dark:bg-green-700 rounded-md shadow-sm w-full flex flex-row justify-center items-center relative text-white font-medium text-xl"
          >
            <font-awesome-icon :icon="['fas', '2']" />
            <div class="absolute top-2 left-2 w-1 h-1 bg-green-400 rounded-full" />
            <div class="absolute top-2 right-2 w-1 h-1 bg-green-400 rounded-full" />
            <div class="absolute bottom-2 left-2 w-1 h-1 bg-green-400 rounded-full" />
            <div class="absolute bottom-2 right-2 w-1 h-1 bg-green-400 rounded-full" />
          </div>
        </div>
        <div class="col-span-4 flex flex-col items-center justify-end">
          <div class="mb-4 flex flex-col flex-nowrap justify-center items-center w-full">
            <div>
              <img
                src="https://customshoescreator.pl/wp-content/uploads/2022/12/malowanie-ubran-2-edited-1.jpg" 
                alt="" 
                class="p-1 bg-gray-300 dark:bg-gray-600 rounded-full shadow-sm text-white top-3-img top-3-img-level-1"
              />
            </div>
            <div class="text-sm text-gray-900 dark:text-gray-500 font-medium">Projekt #33</div>
          </div>
          <div 
            style="height: 200px;"
            class="bg-rose-200 dark:bg-rose-700 rounded-md shadow-sm w-full flex flex-row justify-center items-center relative text-white font-medium text-xl"
          >
            <font-awesome-icon :icon="['fas', 'trophy']" />
            <div class="absolute top-2 left-2 w-1 h-1 bg-rose-400 rounded-full" />
            <div class="absolute top-2 right-2 w-1 h-1 bg-rose-400 rounded-full" />
            <div class="absolute bottom-2 left-2 w-1 h-1 bg-rose-400 rounded-full" />
            <div class="absolute bottom-2 right-2 w-1 h-1 bg-rose-400 rounded-full" />
          </div>
        </div>
        <div class="col-span-4 flex flex-col items-center justify-end">
          <div class="mb-4 flex flex-col flex-nowrap justify-center items-center w-full">
            <div>
              <img
                src="https://customshoescreator.pl/wp-content/uploads/2022/04/sneaker-custom-malowanie-butow-custom-butow-customshoescreator-but-sm-1.webp" 
                alt="" 
                class="p-1 bg-gray-300 dark:bg-gray-600 rounded-full shadow-sm text-white top-3-img top-3-img-level-3"
              />
            </div>
            <div class="text-sm text-gray-900 dark:text-gray-500 font-medium">Projekt #23</div>
          </div>
          <div 
            style="height: 100px;"
            class="bg-sky-200 dark:bg-sky-700 rounded-md shadow-sm w-full flex flex-row justify-center items-center relative text-white font-medium text-xl"
          >
            <font-awesome-icon :icon="['fas', '3']" />
            <div class="absolute top-2 left-2 w-1 h-1 bg-sky-400 rounded-full" />
            <div class="absolute top-2 right-2 w-1 h-1 bg-sky-400 rounded-full" />
            <div class="absolute bottom-2 left-2 w-1 h-1 bg-sky-400 rounded-full" />
            <div class="absolute bottom-2 right-2 w-1 h-1 bg-sky-400 rounded-full" />
          </div>
        </div>
      </div>
      <div>
        <div class="mt-4 flex flex-row flex-wrap justify-between items-center gap-4">
          <div class="flex flex-row flex-nowrap justify-start items-center gap-2 w-full">
            <div class="p-2 bg-rose-300 dark:bg-rose-800 rounded-full h-8 w-8 flex flex-row justify-center items-center">
              <font-awesome-icon :icon="['fas', 'trophy']" style="font-size: 16px;" class="font-medium text-white" />
            </div>
            <div class="text-gray-500 dark:text-gray-400 text-md font-medium">Projekt #33</div>
          </div>
          <table class="text-left border-collapse md:w-3/4 w-full">
            <thead>
              <tr class="bg-gray-100 text-gray-600 text-sm font-medium">
                <th class="py-2 px-4 border-b">Przychód</th>
                <th class="py-2 px-4 border-b">Czas realizacji</th>
              </tr>
            </thead>
            <tbody>
              <tr class="text-gray-700 text-sm">
                <td class="py-2 px-4 border-b">10</td>
                <td class="py-2 px-4 border-b">10</td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="w-full bg-gray-200 dark:bg-gray-700 my-4" style="height: 1px;" />
        <div class="mt-4 flex flex-row flex-wrap justify-between items-center gap-4">
          <div class="flex flex-row flex-nowrap justify-start items-center gap-2 w-full">
            <div class="p-2 bg-green-300 dark:bg-green-800 rounded-full h-8 w-8 flex flex-row justify-center items-center">
              <font-awesome-icon :icon="['fas', '2']" style="font-size: 16px;" class="font-medium text-white" />
            </div>
            <div class="text-gray-500 dark:text-gray-400 text-md font-medium">Projekt #11</div>
          </div>
          <table class="text-left border-collapse md:w-3/4 w-full">
            <thead>
              <tr class="bg-gray-100 text-gray-600 text-sm font-medium">
                <th class="py-2 px-4 border-b">Przychód</th>
                <th class="py-2 px-4 border-b">Czas realizacji</th>
              </tr>
            </thead>
            <tbody>
              <tr class="text-gray-700 text-sm">
                <td class="py-2 px-4 border-b">10</td>
                <td class="py-2 px-4 border-b">10</td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="w-full bg-gray-200 dark:bg-gray-700 my-4" style="height: 1px;" />
        <div class="mt-4 flex flex-row flex-wrap justify-between items-center gap-4">
          <div class="flex flex-row flex-nowrap justify-start items-center gap-2 w-full">
            <div class="p-2 bg-sky-300 dark:bg-sky-800 rounded-full h-8 w-8 flex flex-row justify-center items-center">
              <font-awesome-icon :icon="['fas', '3']" style="font-size: 16px;" class="font-medium text-white" />
            </div>
            <div class="text-gray-500 dark:text-gray-400 text-md font-medium">Projekt #23</div>
          </div>
          <table class="text-left border-collapse md:w-3/4 w-full">
            <thead>
              <tr class="bg-gray-100 text-gray-600 text-sm font-medium">
                <th class="py-2 px-4 border-b">Przychód</th>
                <th class="py-2 px-4 border-b">Czas realizacji</th>
              </tr>
            </thead>
            <tbody>
              <tr class="text-gray-700 text-sm">
                <td class="py-2 px-4 border-b">10</td>
                <td class="py-2 px-4 border-b">10</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <div class="col-span-12 font-medium text-gray-600 mt-4 flex flex-row flex-nowrap items-center justify-between">
      <div>Wykresy</div>
    </div>
    <div class="col-span-12 md:col-span-4 card">
      <h2>Projekty wg. kategorii</h2>
      <DoughnutChart
        :data="doughnutChartData" 
        :options="{ local: 'pl-PL', currency: null }" 
      />
    </div>
    <div class="col-span-12 md:col-span-8 card">
      <h2>Pieniądze</h2>
      <YearlyBarChart 
        :data="yearlyBarChartData" 
        :options="{ local: 'pl-PL', currency: 'PLN', }" 
      />
    </div>
    <div class="col-span-12 2xl:col-span-6 card">
      <h2>Ilość nowych projektów</h2>
      <LineChart 
        :data="lineChartData" 
        :options="{ local: 'pl-PL', currency: null, }" 
      />
    </div>
    <div class="col-span-12 2xl:col-span-6 card">
      <h2>Ilość zakończonych projektów</h2>
      <LineChart 
        :data="lineChartData" 
        :options="{ local: 'pl-PL', currency: null, }" 
      />
    </div>
  </div>
</template>

<script setup>
import '@/Pages/Dashboard/Index/Components/ChartRegister.js'
import LineChart from '@/Pages/Dashboard/Index/Components/LineChart.vue'
import DoughnutChart from '@/Pages/Dashboard/Index/Components/DoughnutChart.vue'
import YearlyBarChart from '@/Pages/Dashboard/Index/Components/YearlyBarChart.vue'
import DateRangePicker from '@/Pages/Dashboard/Index/Components/DateRangePicker.vue'
import ViewToggle from '@/Pages/Dashboard/Index/Components/ViewToggle.vue'
import {  ref } from 'vue'
import { Link } from '@inertiajs/vue3'

const top3UsersSelectedView = ref(0)
const top3ProjectSelectedView = ref(0)
const dateRange = ref(null)

const yearlyBarChartData = {
  2023: {
    labels: ['Styczeń', 'Luty', 'Marzec', 'Kwiecień', 'Maj', 'Czerwiec', 'Lipiec', 'Sierpień', 'Wrzesień', 'Październik', 'Listopad', 'Grudzień'],
    datasets: [
      {
        label: 'Przychód',
        data: [1000, 1200, 1500, 1300, 1700, 2000, 2200, 2100, 1900, 1800, 1600, 1400],
      },
      {
        label: 'Dochód',
        data: [800, 1000, 1200, 1100, 1500, 1800, 2000, 1900, 1700, 1600, 1400, 1200],
      },
      {
        label: 'Wydatki',
        data: [200, 200, 300, 200, 200, 200, 200, 200, 200, 200, 200, 200],
      },
      {
        label: 'Zysk',
        data: [600, 800, 900, 900, 1300, 1600, 1800, 1700, 1500, 1400, 1200, 1000],
      },
    ],
  },
  2024: {
    labels: ['Styczeń', 'Luty', 'Marzec', 'Kwiecień', 'Maj', 'Czerwiec', 'Lipiec', 'Sierpień', 'Wrzesień', 'Październik', 'Listopad', 'Grudzień'],
    datasets: [
      {
        label: 'Przychód',
        data: [1200, 1400, 1700, 1500, 1900, 2200, 2400, 2300, 2100, 2000, 1800, 1600],
      },
      {
        label: 'Dochód',
        data: [1000, 1200, 1400, 1300, 1700, 2000, 2200, 2100, 1900, 1800, 1600, 1400],
      },
      {
        label: 'Wydatki',
        data: [200, 200, 300, 200, 200, 200, 200, 200, 200, 200, 200, 200],
      },
      {
        label: 'Zysk',
        data: [800, 1000, 1100, 1100, 1500, 1800, 2000, 1900, 1700, 1600, 1400, 1200],
      },
    ],
  },
}

const doughnutChartData = {
  labels: ['Renowacja butów', 'Personalizacja butów', 'Personalizacja ubrań', 'Haft ręczny', 'Haft komputerowy', 'Inne'],
  datasets: [
    {
      label: '2023',
      data: [10, 23, 15, 5, 2, 1], 
    },
    {
      label: '2024',
      data: [15, 30, 20, 10, 5, 2],
    },
  ],
}

const lineChartData = {
  labels: ['Styczeń', 'Luty', 'Marzec', 'Kwiecień', 'Maj', 'Czerwiec', 'Lipiec', 'Sierpień', 'Wrzesień', 'Październik', 'Listopad', 'Grudzień'],
  datasets: [
    {
      label: '2023',
      data: [40, 39, 10, 40, 45, 80, 40, 72, 88, 105, 13, 0],
    },
    {
      label: '2024',
      data: [60, 55, 32, 10, 2, 12, 53, 23, 40, 55, 13, 100],
    },

  ],
}


</script>

<style scoped>
.card {
  @apply bg-gray-100 shadow-sm rounded-md p-4 transition-all duration-200 ease-in-out dark:bg-gray-800;
}

.card:hover {
  @apply shadow-md;
}

.card h2 {
  @apply text-gray-400 dark:text-gray-500 text-sm font-medium mb-2;
}

.card .total {
  @apply text-3xl font-bold text-gray-800 dark:text-gray-400;
}

table {
  @apply w-full text-left border-collapse;
}

th, td {
  @apply py-2 px-4 border-b dark:border-gray-600 border-gray-200;
}

th {
  @apply bg-gray-200 text-gray-600 text-sm font-medium border border-gray-300 dark:border-gray-500 dark:bg-gray-700 dark:text-gray-400; 
}

td {
  @apply text-gray-700 text-sm dark:text-gray-300;
}

.top-3-img {
  aspect-ratio: 1;
  object-fit: cover;
  max-width: 120px;
}
</style>

