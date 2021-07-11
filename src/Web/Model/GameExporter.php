<?php

declare(strict_types=1);

namespace VDOLog\Web\Model;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use VDOLog\Core\Domain\Game;

use function strlen;

class GameExporter
{
    public function export(Game $game): Spreadsheet
    {
        $spreadsheet = new Spreadsheet();
        $spreadsheet->removeSheetByIndex(0);

        $this->appendProtocol($spreadsheet, $game);

        return $spreadsheet;
    }

    private function appendProtocol(Spreadsheet $spreadsheet, Game $game): void
    {
        $data   = [];
        $data[] = ['Sender', 'Empfänger', 'Uhrzeit', 'Inhalt'];
        foreach ($game->getProtocol() as $protocol) {
            $data[] = [
                'sender' => $protocol->getSender() ?? '10',
                'recipent' => $protocol->getRecipent() ?? (strlen($protocol->getSender()) === 0 ? 'alle' : '10'),
                'createdAt' => $protocol->getCreatedAt(),
                'content' => $protocol->getContent(),
            ];
        }

        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle('Funkprotokoll');
        $sheet->fromArray($data);
    }
}
