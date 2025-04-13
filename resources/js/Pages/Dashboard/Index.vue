<template>
  <div class="grid grid-cols-12 gap-2">
    <div class="col-span-4 font-medium  flex flex-row flex-nowrap items-center justify-between border border-solid border-gray-100 rounded-md p-4 shadow-sm text-xl">
      Projekty
      <font-awesome-icon :icon="['far', 'hand-point-right']" class="text-2xl" />
    </div>

    <div class="col-span-2 card text-center" style="background-color: rgb(253 224 71 / .5) !important">
      <font-awesome-icon :icon="['fas', 'pause']" class="!text-yellow-400 text-xl" />
      <h2 class="!text-gray-600">Oczekujące</h2>
      <div class="font-bold text-2xl">10</div>
    </div>

    <div class="col-span-2 card text-center" style="background-color: rgb(251 146 60 / .5) !important">
      <font-awesome-icon :icon="['fas', 'screwdriver-wrench']" class="!text-orange-400 text-xl" />
      <h2 class="!text-gray-600">W trakcie</h2>
      <div class="font-bold text-2xl">10</div>
    </div>

    <div class="col-span-2 card text-center" style="background-color: rgb(248 113 113 / .5) !important;">
      <font-awesome-icon :icon="['fas', 'triangle-exclamation']" class="!text-red-400 text-xl" />
      <h2 class="!text-gray-600">Po czasie</h2>
      <div class="font-bold text-2xl">10</div>
    </div>

    <div class="col-span-2 card text-center " style="background-color: rgb(74 222 128 / .5) !important;">
      <font-awesome-icon :icon="['fas', 'flag-checkered']" class="!text-green-400 text-xl" />
      <h2 class="!text-gray-600">Zakończone</h2>
      <div class="font-bold text-2xl ">10</div>
    </div>

    <div class="col-span-12 font-medium text-gray-600 mt-4 flex flex-row flex-nowrap items-center justify-between">
      <div>Całkowite wartości</div>
    </div>

    <div class="col-span-4 card">
      <h2>Stan konta</h2>
      <div class="flex flex-row flex-nowrap items-center gap-4">
        <font-awesome-icon :icon="['fas', 'wallet']" style="font-size: 40px;" class="text-indigo-400" />
        <div class="text-gray-800 font-medium text-2xl">12137.12 <span>zł</span></div>
      </div>
    </div>
    
    <div class="col-span-8" />

    

    <div class="col-span-4 card">
      <h2>Użytkownicy</h2>
      <div class="total">10</div>
    </div>
    <div class="col-span-4 card">
      <h2>Klienci</h2>
      <div class="total">23</div>
    </div>
    <div class="col-span-4 card">
      <h2>Projekty</h2>
      <div class="total">56</div>
    </div>
    <div class="col-span-4 card">
      <h2>Przychód</h2>
      <div class="total">
        1215 <span>zł</span> 
      </div>
    </div>
    <div class="col-span-4 card">
      <h2>Koszty</h2>
      <div class="total">
        230 <span>zł</span> 
      </div>
    </div>
    <div class="col-span-4 card">
      <h2>Zysk</h2>
      <div class="total">
        7612 <span>zł</span> 
      </div>
    </div>

    
    
    <div class="col-span-12 font-medium text-gray-600 mt-4 flex flex-row flex-nowrap items-center justify-between">
      <div>Podsumowanie KPI</div>
      <div class="flex flex-row flex-nowrap justify-end items-start gap-2">
        <div 
          class="col-span-2 text-center" 
          style="font-size: 10px;"
        >
          od
        </div>
        <button 
          style="font-size: 10px"
          class="col-span-3 text-center border border-gray-950 border-solid rounded-md font-medium px-4 bg-gray-100"
        >
          2025-04
        </button>
        <div 
          class="col-span-2 text-center" 
          style="font-size: 10px;"
        >
          do
        </div>
        <button 
          style="font-size: 10px"
          class="col-span-3 text-center border border-gray-950 border-solid rounded-md font-medium  px-4 bg-gray-100"
        >
          2025-05
        </button>
      </div>
    </div>

    <div class="col-span-4 card">
      <div class="flex flex-row flex-nowrap justify-start items-center gap-4 mb-4">
        <div class="p-2 bg-indigo-400 w-16 h-16 rounded-xl flex items-center justify-center">
          <font-awesome-icon :icon="['fas', 'people-group']" class="text-gray-50" style="font-size: 30px;" />
        </div>
        <div class="text-gray-800 font-medium text-xl">Klienci</div>
      </div>
      <div class="flex flex-col gap-2">
        <div>
          <div class="text-gray-400">Nowi</div>
          <div class="font-medium text-gray-600 text-xl flex flex-row flex-nowrap justify-between items-center">
            <span>10</span>
            <span class="text-xs text-green-500">
              <font-awesome-icon :icon="['fas', 'caret-up']" />
              2%
            </span>
          </div>
        </div>
        <div>
          <div class="text-gray-400">Powracający</div>
          <div class="font-medium text-gray-600 text-xl flex flex-row flex-nowrap justify-between items-center">
            <span>10</span>
            <span class="text-xs text-green-500">
              <font-awesome-icon :icon="['fas', 'caret-up']" />
              2%
            </span>
          </div>
        </div>
      </div>
    </div>
    <div class="col-span-4 card">
      <div class="flex flex-row flex-nowrap justify-start items-center gap-4 mb-4">
        <div class="p-2 bg-indigo-400 w-16 h-16 rounded-xl flex items-center justify-center">
          <font-awesome-icon :icon="['fas', 'pen-ruler']" class="text-gray-50" style="font-size: 30px;" />
        </div>
        <div class="text-gray-800 font-medium text-xl">Projekty</div>
      </div>
      <div class="flex flex-col gap-2">
        <div>
          <div class="text-gray-400">Nowe</div>
          <div class="font-medium text-gray-600 text-xl flex flex-row flex-nowrap justify-between items-center">
            <span>10</span>
            <span class="text-xs text-green-500">
              <font-awesome-icon :icon="['fas', 'caret-up']" />
              2%
            </span>
          </div>
        </div>
        <div>
          <div class="text-gray-400">Zakończone</div>
          <div class="font-medium text-gray-600 text-xl flex flex-row flex-nowrap justify-between items-center">
            <span>10</span>
            <span class="text-xs text-green-500">
              <font-awesome-icon :icon="['fas', 'caret-up']" />
              2%
            </span>
          </div>
        </div>
        <div>
          <div class="text-gray-400">Średni czas realizacji</div>
          <div class="font-medium text-gray-600 text-xl flex flex-row flex-nowrap justify-between items-center">
            <span>10</span>
            <span class="text-xs text-green-500">
              <font-awesome-icon :icon="['fas', 'caret-up']" />
              2%
            </span>
          </div>
        </div>
      </div>
    </div>
    <div class="col-span-4 card">
      <div class="flex flex-row flex-nowrap justify-start items-center gap-4 mb-4">
        <div class="p-2 bg-indigo-400 w-16 h-16 rounded-xl flex items-center justify-center">
          <font-awesome-icon :icon="['fas', 'sack-dollar']" class="text-gray-50" style="font-size: 30px;" />
        </div>
        <div class="text-gray-800 font-medium text-xl">Pieniądze</div>
      </div>
      <div class="flex flex-col gap-2">
        <div>
          <div class="text-gray-400">Przychód</div>
          <div class="font-medium text-gray-600 text-xl flex flex-row flex-nowrap justify-between items-center">
            <span>10 zł</span>
            <span class="text-xs text-green-500">
              <font-awesome-icon :icon="['fas', 'caret-up']" />
              2%
            </span>
          </div>
        </div>
        <div>
          <div class="text-gray-400">Koszty</div>
          <div class="font-medium text-gray-600 text-xl flex flex-row flex-nowrap justify-between items-center">
            <span>10 zł</span>
            <span class="text-xs text-green-500">
              <font-awesome-icon :icon="['fas', 'caret-up']" />
              2%
            </span>
          </div>
        </div>
        <div>
          <div class="text-gray-400">Zysk</div>
          <div class="font-medium text-gray-600 text-xl flex flex-row flex-nowrap justify-between items-center">
            <span>10 zł</span>
            <span class="text-xs text-green-500">
              <font-awesome-icon :icon="['fas', 'caret-up']" />
              2%
            </span>
          </div>
        </div>
      </div>
    </div>
    <div class="col-span-2 card">
      <div class="text-gray-800 font-medium text-xs">
        <div>Renowacja butów</div>
        <div class="font-medium text-gray-600 text-xl flex flex-row flex-nowrap justify-between items-center">
          <span>10</span>
          <span class="text-xs text-green-500">
            <font-awesome-icon :icon="['fas', 'caret-up']" />
            2%
          </span>
        </div>
      </div>
    </div>
    <div class="col-span-2 card">
      <div class="text-gray-800 font-medium text-xs">
        <div>Personalizacja butów</div>
        <div class="font-medium text-gray-600 text-xl flex flex-row flex-nowrap justify-between items-center">
          <span>10</span>
          <span class="text-xs text-green-500">
            <font-awesome-icon :icon="['fas', 'caret-up']" />
            2%
          </span>
        </div>
      </div>
    </div>
    <div class="col-span-2 card">
      <div class="text-gray-800 font-medium text-xs">
        <div>Personalizacja ubrań</div>
        <div class="font-medium text-gray-600 text-xl flex flex-row flex-nowrap justify-between items-center">
          <span>10</span>
          <span class="text-xs text-green-500">
            <font-awesome-icon :icon="['fas', 'caret-up']" />
            2%
          </span>
        </div>
      </div>
    </div>
    <div class="col-span-2 card">
      <div class="text-gray-800 font-medium text-xs">
        <div>Haft ręczny</div>
        <div class="font-medium text-gray-600 text-xl flex flex-row flex-nowrap justify-between items-center">
          <span>10</span>
          <span class="text-xs text-green-500">
            <font-awesome-icon :icon="['fas', 'caret-up']" />
            2%
          </span>
        </div>
      </div>
    </div>
    <div class="col-span-2 card">
      <div class="text-gray-800 font-medium text-xs">
        <div>Haft komputerowy</div>
        <div class="font-medium text-gray-600 text-xl flex flex-row flex-nowrap justify-between items-center">
          <span>10</span>
          <span class="text-xs text-green-500">
            <font-awesome-icon :icon="['fas', 'caret-up']" />
            2%
          </span>
        </div>
      </div>
    </div>
    <div class="col-span-2 card">
      <div class="text-gray-800 font-medium text-xs">
        <div>Inne</div>
        <div class="font-medium text-gray-600 text-xl flex flex-row flex-nowrap justify-between items-center">
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



    <div class="col-span-6 card">
      <div class="col-span-12 flex flex-row flex-nowrap justify-center items-center">
        <div class="bg-gray-200 rounded-full px-1 py-1 flex flex-row flex-nowrap justify-between items-center gap-2 border border-solid border-gray-300">
          <button class="shadow-md bg-white rounded-full px-2 py-1 text-gray-800 text-xs font-medium">Ostatnie 30 dni</button>
          <button class="rounded-full px-2 py-1 text-gray-800 text-xs">Od zawsze</button>
        </div>
      </div>
      <div class="flex flex-col flex-nowrap justify-center items-center my-4">
        <h2 class="!text-xl !text-gray-700">Użytkownicy</h2>
        <p class="text-center -mt-2 text-sm text-gray-400">Wygenerowany przychód</p>
      </div>
      <div class="grid grid-cols-12 gap-2 mt-8">
        <div class="col-span-4 flex flex-col items-center justify-end">
          <div class="mb-4 flex flex-col flex-nowrap justify-center items-center w-full">
            <div>
              <font-awesome-icon :icon="['fas', 'user']" class="p-3 bg-gray-300 rounded-full shadow-sm text-white" style="font-size: 20px;" />
            </div>
            <div class="text-sm text-gray-900 font-medium">Filip</div>
          </div>
          <div 
            style="height: 150px;"
            class="bg-green-200 rounded-md shadow-sm w-full flex flex-row justify-center items-center relative text-white font-medium text-xl"
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
              <font-awesome-icon :icon="['fas', 'user']" class="p-3 bg-gray-300 rounded-full shadow-sm text-white" style="font-size: 20px;" />
            </div>
            <div class="text-sm text-gray-900 font-medium">Aleksandra</div>
          </div>
          <div 
            style="height: 200px;"
            class="bg-rose-200 rounded-md shadow-sm w-full flex flex-row justify-center items-center relative text-white font-medium text-xl"
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
              <font-awesome-icon :icon="['fas', 'user']" class="p-3 bg-gray-300 rounded-full shadow-sm text-white" style="font-size: 20px;" />
            </div>
            <div class="text-sm text-gray-900 font-medium">Weronika</div>
          </div>
          <div 
            style="height: 100px;"
            class="bg-sky-200 rounded-md shadow-sm w-full flex flex-row justify-center items-center relative text-white font-medium text-xl"
          >
            <font-awesome-icon :icon="['fas', '3']" />
            <div class="absolute top-2 left-2 w-1 h-1 bg-sky-400 rounded-full" />
            <div class="absolute top-2 right-2 w-1 h-1 bg-sky-400 rounded-full" />
            <div class="absolute bottom-2 left-2 w-1 h-1 bg-sky-400 rounded-full" />
            <div class="absolute bottom-2 right-2 w-1 h-1 bg-sky-400 rounded-full" />
          </div>
        </div>
      </div>
      <div class="mt-4 flex flex-row flex-nowrap justify-between items-center gap-4">
        <div class="flex flex-row flex-nowrap justify-start items-center gap-2 w-1/4">
          <div class="p-2 bg-rose-300 rounded-full h-8 w-8 flex flex-row justify-center items-center">
            <font-awesome-icon :icon="['fas', 'trophy']" style="font-size: 16px;" class="font-medium text-white" />
          </div>
          <div class="text-gray-500 text-md font-medium">Aleksandra</div>
        </div>
        <table class="text-left border-collapse w-3/4">
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
      <div class="w-full bg-gray-200 my-4" style="height: 1px;" />
      <div class="mt-4 flex flex-row flex-nowrap justify-between items-center gap-4">
        <div class="flex flex-row flex-nowrap justify-start items-center gap-2 w-1/4">
          <div class="p-2 bg-green-300 rounded-full h-8 w-8 flex flex-row justify-center items-center">
            <font-awesome-icon :icon="['fas', '2']" style="font-size: 16px;" class="font-medium text-white" />
          </div>
          <div class="text-gray-500 text-md font-medium">Filip</div>
        </div>
        <table class="text-left border-collapse w-3/4">
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
      <div class="w-full bg-gray-200 my-4" style="height: 1px;" />
      <div class="mt-4 flex flex-row flex-nowrap justify-between items-center gap-4">
        <div class="flex flex-row flex-nowrap justify-start items-center gap-2 w-1/4">
          <div class="p-2 bg-sky-300 rounded-full h-8 w-8 flex flex-row justify-center items-center">
            <font-awesome-icon :icon="['fas', '3']" style="font-size: 16px;" class="font-medium text-white" />
          </div>
          <div class="text-gray-500 text-md font-medium">Weronika</div>
        </div>
        <table class="text-left border-collapse w-3/4">
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
    <div class="col-span-6 card">
      <div class="col-span-12 flex flex-row flex-nowrap justify-center items-center">
        <div class="bg-gray-200 rounded-full px-1 py-1 flex flex-row flex-nowrap justify-between items-center gap-2 border border-solid border-gray-300">
          <button class="shadow-md bg-white rounded-full px-2 py-1 text-gray-800 text-xs font-medium">Ostatnie 30 dni</button>
          <button class="rounded-full px-2 py-1 text-gray-800 text-xs">Od zawsze</button>
        </div>
      </div>
      <div class="flex flex-col flex-nowrap justify-center items-center my-4">
        <h2 class="!text-xl !text-gray-700">Projekty</h2>
        <p class="text-center -mt-2 text-sm text-gray-400">Wygenerowany przychód</p>
      </div>
      <div class="grid grid-cols-12 gap-2 mt-8">
        <div class="col-span-4 flex flex-col items-center justify-end">
          <div class="mb-4 flex flex-col flex-nowrap justify-center items-center w-full">
            <div>
              <img
                src="https://customshoescreator.pl/wp-content/uploads/2022/04/malowanie-butow-na-zamowienie-custom-shoes-creator.webp" 
                alt="" 
                class="p-3 bg-gray-300 rounded-full shadow-sm text-white"
                style="width: 200px; object-fit: cover; aspect-ratio: 1;"
              />
            </div>
            <div class="text-sm text-gray-900 font-medium">Projekt #11</div>
          </div>
          <div 
            style="height: 150px;"
            class="bg-green-200 rounded-md shadow-sm w-full flex flex-row justify-center items-center relative text-white font-medium text-xl"
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
                class="p-3 bg-gray-300 rounded-full shadow-sm text-white"
                style="width: 250px; object-fit: cover; aspect-ratio: 1;"
              />
            </div>
            <div class="text-sm text-gray-900 font-medium">Projekt #33</div>
          </div>
          <div 
            style="height: 200px;"
            class="bg-rose-200 rounded-md shadow-sm w-full flex flex-row justify-center items-center relative text-white font-medium text-xl"
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
                class="p-3 bg-gray-300 rounded-full shadow-sm text-white"
                style="width: 150px; object-fit: cover; aspect-ratio: 1;"
              />
            </div>
            <div class="text-sm text-gray-900 font-medium">Projekt #23</div>
          </div>
          <div 
            style="height: 100px;"
            class="bg-sky-200 rounded-md shadow-sm w-full flex flex-row justify-center items-center relative text-white font-medium text-xl"
          >
            <font-awesome-icon :icon="['fas', '3']" />
            <div class="absolute top-2 left-2 w-1 h-1 bg-sky-400 rounded-full" />
            <div class="absolute top-2 right-2 w-1 h-1 bg-sky-400 rounded-full" />
            <div class="absolute bottom-2 left-2 w-1 h-1 bg-sky-400 rounded-full" />
            <div class="absolute bottom-2 right-2 w-1 h-1 bg-sky-400 rounded-full" />
          </div>
        </div>
      </div>
      <div class="mt-4 flex flex-row flex-nowrap justify-between items-center gap-4">
        <div class="flex flex-row flex-nowrap justify-start items-center gap-2 w-1/4">
          <div class="p-2 bg-rose-300 rounded-full h-8 w-8 flex flex-row justify-center items-center">
            <font-awesome-icon :icon="['fas', 'trophy']" style="font-size: 16px;" class="font-medium text-white" />
          </div>
          <div class="text-gray-500 text-md font-medium">Projekt #33</div>
        </div>
        <table class="text-left border-collapse w-3/4">
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
      <div class="w-full bg-gray-200 my-4" style="height: 1px;" />
      <div class="mt-4 flex flex-row flex-nowrap justify-between items-center gap-4">
        <div class="flex flex-row flex-nowrap justify-start items-center gap-2 w-1/4">
          <div class="p-2 bg-green-300 rounded-full h-8 w-8 flex flex-row justify-center items-center">
            <font-awesome-icon :icon="['fas', '2']" style="font-size: 16px;" class="font-medium text-white" />
          </div>
          <div class="text-gray-500 text-md font-medium">Projekt #11</div>
        </div>
        <table class="text-left border-collapse w-3/4">
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
      <div class="w-full bg-gray-200 my-4" style="height: 1px;" />
      <div class="mt-4 flex flex-row flex-nowrap justify-between items-center gap-4">
        <div class="flex flex-row flex-nowrap justify-start items-center gap-2 w-1/4">
          <div class="p-2 bg-sky-300 rounded-full h-8 w-8 flex flex-row justify-center items-center">
            <font-awesome-icon :icon="['fas', '3']" style="font-size: 16px;" class="font-medium text-white" />
          </div>
          <div class="text-gray-500 text-md font-medium">Projekt #23</div>
        </div>
        <table class="text-left border-collapse w-3/4">
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
    <div class="col-span-12 font-medium text-gray-600 mt-4 flex flex-row flex-nowrap items-center justify-between">
      <div>Wykresy</div>
    </div>
    <div class="col-span-4 card">
      <h2>Projekty wg. kategorii</h2>
      <DoughnutChart
        :data="doughnutChartData" 
        :options="doughnutChartOptions({ local: 'pl-PL', currency: null, theme: 'light' })" 
        :colors="colors({ theme: 'dark' })"
      />
    </div>
    <div class="col-span-8 card">
      <h2>Ilość nowych projektów</h2>
      <LineChart 
        :data="lineChartData" 
        :options="lineChartOptions({ local: 'pl-PL', currency: null, })" 
        :colors="colors({ theme: 'dark' })"
      />
    </div>
  </div>
</template>

<script setup>
import '@/Pages/Dashboard/Index/Components/ChartRegister.js'
import { colors } from '@/Pages/Dashboard/Index/Components/ChartColors.js'
import { options as doughnutChartOptions } from '@/Pages/Dashboard/Index/Components/DoughnutChartOptions.js'
import { options as lineChartOptions } from '@/Pages/Dashboard/Index/Components/LineChartOptions.js'
import LineChart from '@/Pages/Dashboard/Index/Components/LineChart.vue'
import DoughnutChart from '@/Pages/Dashboard/Index/Components/DoughnutChart.vue'

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
  @apply bg-gray-100 shadow-sm rounded-md p-4;
}

.card h2 {
  @apply text-gray-400 text-sm font-medium mb-2;
}

.card .total {
  @apply text-3xl font-bold text-gray-800;
}

table {
  @apply w-full text-left border-collapse;
}

th, td {
  @apply py-2 px-4 border-b;
}

th {
  @apply bg-gray-100 text-gray-600 text-sm font-medium border border-gray-300; /* Dodano obramowanie */
}

td {
  @apply text-gray-700 text-sm;
}
</style>
  
  