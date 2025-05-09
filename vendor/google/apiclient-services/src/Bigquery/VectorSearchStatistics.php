<?php
/*
 * Copyright 2014 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a copy of
 * the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations under
 * the License.
 */

namespace Google\Service\Bigquery;

class VectorSearchStatistics extends \Google\Collection
{
  protected $collection_key = 'storedColumnsUsages';
  protected $indexUnusedReasonsType = IndexUnusedReason::class;
  protected $indexUnusedReasonsDataType = 'array';
  /**
   * @var string
   */
  public $indexUsageMode;
  protected $storedColumnsUsagesType = StoredColumnsUsage::class;
  protected $storedColumnsUsagesDataType = 'array';

  /**
   * @param IndexUnusedReason[]
   */
  public function setIndexUnusedReasons($indexUnusedReasons)
  {
    $this->indexUnusedReasons = $indexUnusedReasons;
  }
  /**
   * @return IndexUnusedReason[]
   */
  public function getIndexUnusedReasons()
  {
    return $this->indexUnusedReasons;
  }
  /**
   * @param string
   */
  public function setIndexUsageMode($indexUsageMode)
  {
    $this->indexUsageMode = $indexUsageMode;
  }
  /**
   * @return string
   */
  public function getIndexUsageMode()
  {
    return $this->indexUsageMode;
  }
  /**
   * @param StoredColumnsUsage[]
   */
  public function setStoredColumnsUsages($storedColumnsUsages)
  {
    $this->storedColumnsUsages = $storedColumnsUsages;
  }
  /**
   * @return StoredColumnsUsage[]
   */
  public function getStoredColumnsUsages()
  {
    return $this->storedColumnsUsages;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(VectorSearchStatistics::class, 'Google_Service_Bigquery_VectorSearchStatistics');
