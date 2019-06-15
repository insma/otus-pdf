<?php
/**
 * Otus PDF - PDF document generation library
 * Copyright(C) 2019 Maciej Klemarczyk
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 */
namespace trogon\otuspdf\meta;

class DocumentInfo extends \trogon\otuspdf\base\BaseObject
{
    public $title;
    public $author;
    public $subject;
    public $keywords;
    private $creationDate;
    private $modificationDate;

    public function getCreator()
    {
        return 'Otus PDF by Trogon Software';
    }

    public function getProducer()
    {
        return 'Otus PDF by Trogon Software';
    }

    public function getCreationDate()
    {
        if (empty($this->creationDate)) {
            $this->generateDateInfo();
        }
        return $this->creationDate;
    }

    public function getModificationDate()
    {
        if (empty($this->modificationDate)) {
            $this->generateDateInfo();
        }
        return $this->modificationDate;
    }

    private function generateDateInfo()
    {
        $dateTime = \date('YmdHis');
        $timezone = \str_replace(':', '\'', \date('P'));
        $this->modificationDate = $this->creationDate = "D:{$dateTime}{$timezone}'";
    }
}
