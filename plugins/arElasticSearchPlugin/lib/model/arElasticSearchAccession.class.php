<?php

/*
 * This file is part of the Access to Memory (AtoM) software.
 *
 * Access to Memory (AtoM) is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Access to Memory (AtoM) is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Access to Memory (AtoM).  If not, see <http://www.gnu.org/licenses/>.
 */

class arElasticSearchAccession extends arElasticSearchModelBase
{
  public function populate()
  {
    $accessions = QubitAccession::getAll();

    $this->count = count($accessions);

    foreach ($accessions as $key => $accession)
    {
      $data = self::serialize($accession);

      $this->search->addDocument($data, 'QubitAccession');

      $this->logEntry($accession->__toString(), $key + 1);
    }
  }

  public static function serialize($object)
  {
    $serialized = array();

    $serialized['id'] = $object->id;
    $serialized['slug'] = $object->slug;

    $serialized['identifier'] = $object->identifier;

    $serialized['createdAt'] = arElasticSearchPluginUtil::convertDate($object->createdAt);
    $serialized['updatedAt'] = arElasticSearchPluginUtil::convertDate($object->updatedAt);

    $serialized['sourceCulture'] = $object->sourceCulture;
    $serialized['i18n'] = self::serializeI18ns($object->id, array('QubitAccession'));

    return $serialized;
  }

  public static function update($object)
  {
    $data = self::serialize($object);

    QubitSearch::getInstance()->addDocument($data, 'QubitAccession');

    return true;
  }
}