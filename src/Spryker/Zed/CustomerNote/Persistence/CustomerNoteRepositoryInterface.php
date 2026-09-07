<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerNote\Persistence;

use Generated\Shared\Transfer\CustomerNoteCollectionTransfer;
use Generated\Shared\Transfer\CustomerNoteCriteriaTransfer;

interface CustomerNoteRepositoryInterface
{
    /**
     * Specification:
     * - Fetches customer notes by id customer
     *
     * @param int $idCustomer
     *
     * @return \Generated\Shared\Transfer\CustomerNoteCollectionTransfer
     */
    public function getCustomerNoteCollectionByIdCustomer(int $idCustomer): CustomerNoteCollectionTransfer;

    /**
     * Specification:
     * - Fetches customer notes matching the criteria.
     * - Filters by `CustomerNoteConditions.customerIds` when set.
     * - Applies `CustomerNoteCriteria.sortCollection`; a field outside
     *   `CustomerNoteConfig::getCustomerNoteCollectionSortableFieldMap()` is ignored.
     * - Orders by newest first when no sort is requested, and always breaks ties on the note id, so
     *   paging over the collection cannot repeat or skip a row.
     * - Applies `CustomerNoteCriteria.pagination` and returns it populated with the result metadata.
     *
     * @return \Generated\Shared\Transfer\CustomerNoteCollectionTransfer
     */
    public function getCustomerNoteCollection(
        CustomerNoteCriteriaTransfer $customerNoteCriteriaTransfer
    ): CustomerNoteCollectionTransfer;
}
