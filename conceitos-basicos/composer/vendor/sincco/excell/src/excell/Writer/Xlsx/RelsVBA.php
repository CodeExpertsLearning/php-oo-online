<?php

namespace Sincco\Excell\Writer\Xlsx;

/**
 *
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   Excell
 * @copyright  Open Software
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 * @version     ##VERSION##, ##DATE##
 */
class RelsVBA extends WriterPart
{
    /**
     * Write relationships for a signed VBA Project
     *
     * @param \Sincco\Excell\SpreadSheet $spreadsheet
     * @throws     \Sincco\Excell\Writer\Exception
     * @return string  XML Output
     */
    public function writeVBARelationships(\Sincco\Excell\SpreadSheet $spreadsheet = null)
    {
        // Create XML writer
        $objWriter = null;
        if ($this->getParentWriter()->getUseDiskCaching()) {
            $objWriter = new \Sincco\Excell\Shared\XMLWriter(\Sincco\Excell\Shared\XMLWriter::STORAGE_DISK, $this->getParentWriter()->getDiskCachingDirectory());
        } else {
            $objWriter = new \Sincco\Excell\Shared\XMLWriter(\Sincco\Excell\Shared\XMLWriter::STORAGE_MEMORY);
        }

        // XML header
        $objWriter->startDocument('1.0', 'UTF-8', 'yes');

        // Relationships
        $objWriter->startElement('Relationships');
        $objWriter->writeAttribute('xmlns', 'http://schemas.openxmlformats.org/package/2006/relationships');
        $objWriter->startElement('Relationship');
        $objWriter->writeAttribute('Id', 'rId1');
        $objWriter->writeAttribute('Type', 'http://schemas.microsoft.com/office/2006/relationships/vbaProjectSignature');
        $objWriter->writeAttribute('Target', 'vbaProjectSignature.bin');
        $objWriter->endElement();
        $objWriter->endElement();

        return $objWriter->getData();
    }
}
