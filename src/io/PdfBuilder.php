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
namespace trogon\otuspdf\io;

use trogon\otuspdf\base\InvalidCallException;
use trogon\otuspdf\io\pdf\PdfArray;
use trogon\otuspdf\io\pdf\PdfCrossReference;
use trogon\otuspdf\io\pdf\PdfDictionary;
use trogon\otuspdf\io\pdf\PdfName;
use trogon\otuspdf\io\pdf\PdfNumber;
use trogon\otuspdf\io\pdf\PdfObject;
use trogon\otuspdf\io\pdf\PdfObjectFactory;
use trogon\otuspdf\io\pdf\PdfObjectReference;
use trogon\otuspdf\io\pdf\PdfStream;
use trogon\otuspdf\io\pdf\PdfString;
use trogon\otuspdf\io\pdf\PdfTrailer;

class PdfBuilder extends \trogon\otuspdf\base\DependencyObject
{
    private $objectFactory;

    public function init()
    {
        parent::init();
        $this->objectFactory = new PdfObjectFactory();
    }

    public function getObjectFactory()
    {
        return $this->objectFactory;
    }

    public function createBasicFont($name, $family)
    {
        $fontObj = $this->objectFactory->create();
        $fontObj->content = new PdfDictionary();
        $fontObj->content->addItem(
            new PdfName(['value' => 'Type']),
            new PdfName(['value' => 'Font'])
        );
        $fontObj->content->addItem(
            new PdfName(['value' => 'Subtype']),
            new PdfName(['value' => 'Type1'])
        );
        $fontObj->content->addItem(
            new PdfName(['value' => 'Name']),
            new PdfName(['value' => $name])
        );
        $fontObj->content->addItem(
            new PdfName(['value' => 'BaseFont']),
            new PdfName(['value' => $family])
        );
        $fontObj->content->addItem(
            new PdfName(['value' => 'Encoding']),
            new PdfName(['value' => 'WinAnsiEncoding'])
        );
        return $fontObj;
    }

    public function createCatalog()
    {
        $catalogObj = $this->objectFactory->create();
        $catalogObj->content = new PdfDictionary();
        $catalogObj->content->addItem(
            new PdfName(['value' => 'Type']),
            new PdfName(['value' => 'Catalog'])
        );
        return $catalogObj;
    }

    public function createCrossReference()
    {
        return new PdfCrossReference();
    }

    public function createDocumentInfo($config = [])
    {
        $dictionary = new PdfDictionary();
        $isEmpty = true;
        foreach ($config as $key => $value) {
            if (!empty($value)) {
                $dictionary->addItem(
                    new PdfName(['value' => $key]),
                    new PdfString(['value' => $value])
                );
                $isEmpty = false;
            }
        }
        if (!$isEmpty) {
            $documentInfoObj = $this->objectFactory->create();
            $documentInfoObj->content = $dictionary;
            return $documentInfoObj;
        }
        return null;
    }

    public function createFontsResource()
    {
        return new PdfDictionary();
    }

    public function createMediaBox($width, $height, $x = 0, $y = 0)
    {
        $mediaBox = new PdfArray();
        $mediaBox->addItem(new PdfNumber(['value' => $x]));
        $mediaBox->addItem(new PdfNumber(['value' => $y]));
        $mediaBox->addItem(new PdfNumber(['value' => $width]));
        $mediaBox->addItem(new PdfNumber(['value' => $height]));
        return $mediaBox;
    }

    public function createOutlines()
    {
        $outlinesObj = $this->objectFactory->create();
        $outlinesObj->content = new PdfDictionary();
        $outlinesObj->content->addItem(
            new PdfName(['value' => 'Type']),
            new PdfName(['value' => 'Outlines'])
        );
        $outlinesObj->content->addItem(
            new PdfName(['value' => 'Count']),
            new PdfNumber(['value' => 0])
        );
        return $outlinesObj;
    }

    public function createPage($pageCollectionObj)
    {
        $pageObj = $this->objectFactory->create();
        $pageObj->content = new PdfDictionary();
        $pageObj->content->addItem(
            new PdfName(['value' => 'Type']),
            new PdfName(['value' => 'Page'])
        );
        $pageObj->content->addItem(
            new PdfName(['value' => 'Parent']),
            new PdfObjectReference(['object' => $pageCollectionObj])
        );
        return $pageObj;
    }

    public function createPageCollection($resourcesDict, $mediaBox)
    {
        $pageCollectionObj = $this->objectFactory->create();
        $pageCollectionObj->content = new PdfDictionary();
        $pageCollectionObj->content->addItem(
            new PdfName(['value' => 'Type']),
            new PdfName(['value' => 'Pages'])
        );
        $pageCollectionObj->content->addItem(
            new PdfName(['value' => 'Kids']),
            new PdfArray()
        );
        $pageCollectionObj->content->addItem(
            new PdfName(['value' => 'Count']),
            new PdfNumber(['value' => 0])
        );
        $pageCollectionObj->content->addItem(
            new PdfName(['value' => 'Resources']),
            $resourcesDict
        );
        $pageCollectionObj->content->addItem(
            new PdfName(['value' => 'MediaBox']),
            $mediaBox
        );
        return $pageCollectionObj;
    }

    public function createPageContent()
    {
        $pageContentObj = $this->objectFactory->create();
        $pageContentObj->content = new PdfDictionary();
        return $pageContentObj;
    }

    public function createProcSet()
    {
        $procSetObj = $this->objectFactory->create();
        $procSetObj->content = new PdfArray();
        $procSetObj->content->addItem(new PdfName(['value' => 'PDF']));
        return $procSetObj;
    }

    public function createResourceCatalog()
    {
        return  new PdfDictionary();
    }

    public function createTrailer($rootObject, $xrefOffset, $xrefSize, $documentInfoObject = null)
    {
        $trailer = new PdfTrailer([
            'xrefOffset' => $xrefOffset
        ]);
        $trailer->content->addItem(
            new PdfName(['value' => 'Size']),
            new PdfNumber(['value' => $xrefSize])
        );
        $trailer->content->addItem(
            new PdfName(['value' => 'Root']),
            new PdfObjectReference(['object' => $rootObject])
        );
        if ($documentInfoObject instanceof PdfObject) {
            $trailer->content->addItem(
                new PdfName(['value' => 'Info']),
                new PdfObjectReference(['object' => $documentInfoObject])
            );
        }
        return $trailer;
    }

    public static function registerFont($fontsDict, $fontObj)
    {
        $fontsDict->addItem(
            new PdfName(['value' => $fontObj->content->getItem('Name')->value]),
            new PdfObjectReference(['object' => $fontObj])
        );
    }

    public static function registerFontsResource($resourcesDict, $fontsDict)
    {
        $resourcesDict->addItem(
            new PdfName(['value' => 'Font']),
            $fontsDict
        );
    }

    public static function registerMediaBox($pageObj, $mediaBoxArray)
    {
        $pageObj->content->addItem(
            new PdfName(['value' => 'MediaBox']),
            $mediaBoxArray
        );
    }

    public static function registerOutlines($catalogObj, $outlinesObj)
    {
        $catalogObj->content->addItem(
            new PdfName(['value' => 'Outlines']),
            new PdfObjectReference(['object' => $outlinesObj])
        );
    }

    public static function registerPage($pageCollectionObj, $pageObj)
    {
        $pageCollectionObj->content->getItem('Kids')->addItem(
            new PdfObjectReference(['object' => $pageObj])
        );
        $pageCollectionObj->content->getItem('Count')->value++;
    }

    public static function registerPageCollection($catalogObj, $pageCollectionObj)
    {
        $catalogObj->content->addItem(
            new PdfName(['value' => 'Pages']),
            new PdfObjectReference(['object' => $pageCollectionObj])
        );
    }

    public static function registerPageContent($pageObj, $pageContentObj)
    {
        $pageObj->content->addItem(
            new PdfName(['value' => 'Contents']),
            new PdfObjectReference(['object' => $pageContentObj])
        );
    }

    public static function registerProcSetResource($resourcesDict, $procSetObj)
    {
        $resourcesDict->addItem(
            new PdfName(['value' => 'ProcSet']),
            new PdfObjectReference(['object' => $procSetObj])
        );
    }

    public static function setStreamContent($object, $streamContent)
    {
        $stream = new PdfStream();
        $stream->value = $streamContent;
        $object->stream = $stream;
        $object->content->addItem(
            new PdfName(['value' => 'Filter']),
            new PdfName(['value' => 'FlateDecode'])
        );
        $object->content->addItem(
            new PdfName(['value' => 'Length']),
            new PdfNumber(['value' => $stream->length])
        );
    }
}
