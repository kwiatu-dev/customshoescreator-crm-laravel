<template>
  <h1 class="title mb-4">
    Kokpit
  </h1>

  <div class="grid grid-cols-12 gap-2">
    <div class="col-span-4 card">
      <h2>Stan konta</h2>
      <div class="flex flex-row flex-nowrap items-center gap-4">
        <font-awesome-icon :icon="['fas', 'wallet']" style="font-size: 40px;" class="text-indigo-400" />
        <div class="text-gray-800 font-medium text-2xl">12137.12 <span>zł</span></div>
      </div>
    </div>
    
    <div class="col-span-12 font-medium text-gray-600 mt-4">Podsumowanie KPI</div>

    <div class="col-span-12 flex flex-row flex-nowrap justify-end items-start gap-4">
      <button 
        style="font-size: 10px"
        class="col-span-3 text-center border border-gray-950 border-solid rounded-md font-medium px-4 bg-gray-100"
      >
        2025-04-14
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
        2025-05-14
      </button>
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
            <span>10</span>
            <span class="text-xs text-green-500">
              <font-awesome-icon :icon="['fas', 'caret-up']" />
              2%
            </span>
          </div>
        </div>
        <div>
          <div class="text-gray-400">Koszty</div>
          <div class="font-medium text-gray-600 text-xl flex flex-row flex-nowrap justify-between items-center">
            <span>10</span>
            <span class="text-xs text-green-500">
              <font-awesome-icon :icon="['fas', 'caret-up']" />
              2%
            </span>
          </div>
        </div>
        <div>
          <div class="text-gray-400">Zysk</div>
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

    <div class="col-span-12 font-medium text-gray-600 mt-4">Całkowite wartości</div>
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
    <div class="col-span-12 card">test</div>
  </div>
</template>

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
</style>
  
  