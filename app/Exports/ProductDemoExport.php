<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

class ProductDemoExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping, WithEvents
{
    protected $categories;
    protected $subCategories;

    public function __construct($data)
    {
        $this->categories = $data['categories'];
        $this->subCategories = $data['subCategories'];
    }

    public function collection()
    {
        return new Collection([]);
    }

    public function headings(): array
    {
        return [
            'Category',
            'SubCategory',
            // Add more headings if needed
        ];
    }

    public function map($row): array
    {
        return [
            isset($this->categories[$row->id]) ? $this->categories[$row->id] : '', // Dropdown column
            isset($this->subCategories[$row->id]) ? $this->subCategories[$row->id] : '', // Dropdown column
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // Define and set validation for Category dropdown
                $categoryValidation = $event->sheet->getCell('A2')->getDataValidation();
                $categoryValidation->setType(DataValidation::TYPE_LIST);
                $categoryValidation->setErrorStyle(DataValidation::STYLE_INFORMATION);
                $categoryValidation->setAllowBlank(false);
                $categoryValidation->setShowInputMessage(true);
                $categoryValidation->setShowErrorMessage(true);
                $categoryValidation->setShowDropDown(true);
                $categoryValidation->setErrorTitle('Input error');
                $categoryValidation->setError('Value is not in list.');
                $categoryValidation->setPromptTitle('Pick from list');
                $categoryValidation->setPrompt('Please pick a value from the dropdown list.');

                // Define the range for the Category dropdown
                $categoryStartRow = 2; // Assuming data starts from row 2
                $categoryEndRow = 1000000; // Assuming you want to apply validation up to row 100
                $categoryValidationRange = 'A' . $categoryStartRow . ':A' . $categoryEndRow;

                // Set the formula of the data validation to refer to the range of cells containing category names
                $categoryValidation->setFormula1('"' . implode(',', $this->categories) . '"');

                // Apply validation to the entire Category column
                $event->sheet->setDataValidation($categoryValidationRange, $categoryValidation);

                // Define and set validation for SubCategory dropdown
                $subCategoryValidation = $event->sheet->getCell('B2')->getDataValidation();
                $subCategoryValidation->setType(DataValidation::TYPE_LIST);
                $subCategoryValidation->setErrorStyle(DataValidation::STYLE_INFORMATION);
                $subCategoryValidation->setAllowBlank(false);
                $subCategoryValidation->setShowInputMessage(true);
                $subCategoryValidation->setShowErrorMessage(true);
                $subCategoryValidation->setShowDropDown(true);
                $subCategoryValidation->setErrorTitle('Input error');
                $subCategoryValidation->setError('Value is not in list.');
                $subCategoryValidation->setPromptTitle('Pick from list');
                $subCategoryValidation->setPrompt('Please pick a value from the dropdown list.');

                // Define the range for the SubCategory dropdown
                $subCategoryStartRow = 2; // Assuming data starts from row 2
                $subCategoryEndRow = 1000000; // Assuming you want to apply validation up to row 100
                $subCategoryValidationRange = 'B' . $subCategoryStartRow . ':B' . $subCategoryEndRow;

                // Set the formula of the data validation to refer to the range of cells containing subcategory names
                $subCategoryValidation->setFormula1('"' . implode(',', $this->subCategories) . '"');

                // Apply validation to the entire SubCategory column
                $event->sheet->setDataValidation($subCategoryValidationRange, $subCategoryValidation);
            },
        ];
    }
}
