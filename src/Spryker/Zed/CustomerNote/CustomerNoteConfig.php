<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Zed\CustomerNote;

use Generated\Shared\Transfer\SpyCustomerNoteEntityTransfer;
use Orm\Zed\CustomerNote\Persistence\Map\SpyCustomerNoteTableMap;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class CustomerNoteConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - How a note records the name of the user who wrote it.
     *
     * @api
     */
    public const string AUTHOR_NAME_FORMAT = '%s %s';

    /**
     * Specification:
     * - Maps the sortable fields of the CustomerNote entity to the corresponding database columns.
     *
     * @api
     *
     * @return array<string, string>
     */
    public function getCustomerNoteCollectionSortableFieldMap(): array
    {
        return [
            SpyCustomerNoteEntityTransfer::CREATED_AT => SpyCustomerNoteTableMap::COL_CREATED_AT,
            SpyCustomerNoteEntityTransfer::UPDATED_AT => SpyCustomerNoteTableMap::COL_UPDATED_AT,
            SpyCustomerNoteEntityTransfer::USERNAME => SpyCustomerNoteTableMap::COL_USERNAME,
        ];
    }
}
