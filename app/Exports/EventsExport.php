<?php

namespace App\Exports;

use App\Models\Event;
use Illuminate\Support\Carbon;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;



use Maatwebsite\Excel\Concerns\Exportable;       // Trait para facilitar a exportação

class EventsExport implements FromQuery, WithMapping, WithHeadings, ShouldAutoSize, WithStyles
{
    use Exportable;

    protected $request;

    /**
     * Construtor recebe a request (filtros vindos do front, por exemplo)
     */
    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * Define a query que será usada para buscar os dados
     */
    public function query()
    {
        $query = Event::query()->with('organizer'); // Carrega o organizador para exibir no Excel

        // Exemplo de filtro (opcional)
        if (!empty($this->request->organizer_id)) {
            $query->where('organizer_id', $this->request->organizer_id);
        }

        return $query;
    }

    /**
     * Define o cabeçalho da planilha
     */
    public function headings(): array
    {
        return [
            'ID',
            'Título',
            'Descrição',
            'Data',
            'Local',
            'Organizador'
        ];
    }

    /**
     * Define como os dados de cada evento serão mapeados na planilha
     */
    public function map($event): array
    {
        return [
            $event->id,
            $event->title,
            $event->description,
            Carbon::parse($event->date)->format('d/m/Y'), // Formatando a data com Carbon
            $event->location,
            $event->organizer->name ?? 'Desconhecido'     // Se não tiver organizador
        ];
    }

    /**
     * Estiliza o cabeçalho da planilha
     */
    public function styles(Worksheet $sheet)
    {
       // Estilizando o cabeçalho
       $sheet->getStyle('A1:H1')->applyFromArray([
        'font' => ['bold' => true],
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => ['rgb' => 'E0E0E0']
        ],
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
                'color' => ['rgb' => '000000']
            ]
        ]
    ]);

    // Ajustando alinhamento da coluna de valores
    $sheet->getStyle('F')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

    // Ajustando alinhamento da coluna de datas
    $sheet->getStyle('G')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    }
}