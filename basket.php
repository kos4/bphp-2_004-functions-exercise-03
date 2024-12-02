<?php

declare(strict_types = 1);

const OPERATION_EXIT = 0;
const OPERATION_ADD = 1;
const OPERATION_DELETE = 2;
const OPERATION_PRINT = 3;
const OPERATION_EDIT = 4;

$operations = [
  OPERATION_EXIT => OPERATION_EXIT . '. Завершить программу.',
  OPERATION_ADD => OPERATION_ADD . '. Добавить товар в список покупок.',
  OPERATION_DELETE => OPERATION_DELETE . '. Удалить товар из списка покупок.',
  OPERATION_PRINT => OPERATION_PRINT . '. Отобразить список покупок.',
  OPERATION_EDIT => OPERATION_EDIT . '. Редактировать товар',
];

$items = [];


do {
  system('clear');
  //    system('cls'); // windows

  $operationNumber = getOperationNumber($operations, $items);
  echo 'Выбрана операция: '  . $operations[$operationNumber] . PHP_EOL;

  switch ($operationNumber) {
    case OPERATION_ADD:
      addProductBasket($items);

      break;
    case OPERATION_DELETE:
      deleteProductBasket($items);

      break;
    case OPERATION_PRINT:
      printBasket($items);

      break;

    case OPERATION_EDIT:
      editProductBasket($items);

      break;
  }

  echo "\n ----- \n";
} while ($operationNumber > 0);

echo 'Программа завершена' . PHP_EOL;

function viewBasketItems(array $items, int $operation = null): void
{
  if (count($items)) {
    if ($operation === OPERATION_DELETE || $operation === OPERATION_EDIT) {
      echo 'Текущий список покупок:' . PHP_EOL;
      echo 'Список покупок: ' . PHP_EOL;
    } else {
      echo 'Ваш список покупок: ' . PHP_EOL;
    }

    foreach ($items as $item) {
      echo implode(": ", $item) . "шт.\n";
    }

    if ($operation === OPERATION_PRINT) {
      echo 'Всего ' . count($items) . ' позиций. '. PHP_EOL;
    }
  } else {
    echo 'Ваш список покупок пуст.' . PHP_EOL;
  }
}

function getOperationNumber(array $operations, array $items): int
{
  do {
    viewBasketItems($items);

    echo 'Выберите операцию для выполнения: ' . PHP_EOL;
    // Проверить, есть ли товары в списке? Если нет, то не отображать пункт про удаление товаров
    echo implode(PHP_EOL, $operations) . PHP_EOL . '> ';
    $operationNumber = (int) trim(fgets(STDIN));

    if (!array_key_exists($operationNumber, $operations)) {
      system('clear');

      echo '!!! Неизвестный номер операции, повторите попытку.' . PHP_EOL;
    }
  } while (!array_key_exists($operationNumber, $operations));

  return $operationNumber;
}

function addProductBasket(array &$items): void
{
  echo "Введение название товара для добавления в список: \n> ";
  $itemName = trim(fgets(STDIN));

  do {
    echo "Введение количество товара для добавления в список: \n> ";
    $itemQuantity = (float) trim(fgets(STDIN));
  } while ($itemQuantity === 0.0);

  $items[] = [
    'name' => $itemName,
    'quantity' => $itemQuantity,
  ];
}

function deleteProductBasket(array &$items): void
{
  // Проверить, есть ли товары в списке? Если нет, то сказать об этом и попросить ввести другую операцию
  viewBasketItems($items, OPERATION_DELETE);

  if (count($items) > 0) {
    echo 'Введение название товара для удаления из списка:' . PHP_EOL . '> ';
    $itemName = trim(fgets(STDIN));

    $index = array_filter($items, function (array $item) use ($itemName) {
      return $item['name'] === $itemName;
    });

    if (count($index)) {
      unset($items[array_key_first($index)]);
    }
  } else {
    pressEnterToContinue();
  }
}

function printBasket(array &$items): void
{
  viewBasketItems($items, OPERATION_PRINT);
  pressEnterToContinue();
}

function pressEnterToContinue(): void
{
  echo 'Нажмите enter для продолжения';
  fgets(STDIN);
}

function editProductBasket(array &$items): void
{
  viewBasketItems($items, OPERATION_EDIT);

  if (count($items) > 0) {
    echo 'Введение название товара для редактирования:' . PHP_EOL . '> ';
    $itemName = trim(fgets(STDIN));

    $index = array_filter($items, function (array $item) use ($itemName) {
      return $item['name'] === $itemName;
    });

    if (count($index)) {
      echo 'Введите новое название:' . PHP_EOL . '> ';
      $newName = trim(fgets(STDIN));

      do {
        echo 'Введите новое количество:' . PHP_EOL . '> ';
        $newQuantity = (float) trim(fgets(STDIN));
      } while ($newQuantity === 0.0);

      $items[array_key_first($index)] = [
        'name' => $newName,
        'quantity' => $newQuantity,
      ];
    } {
      echo 'Товар не найден' . PHP_EOL;
    }
  } else {
    pressEnterToContinue();
  }
}