<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerNote\Business;

use Generated\Shared\Transfer\CustomerNoteCollectionTransfer;
use Generated\Shared\Transfer\CustomerNoteCriteriaTransfer;
use Generated\Shared\Transfer\SpyCustomerNoteEntityTransfer;

interface CustomerNoteFacadeInterface
{
    /**
     * Specification:
     * - Inserts a note to the database
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SpyCustomerNoteEntityTransfer $customerNoteEntityTransfer
     *
     * @return \Generated\Shared\Transfer\SpyCustomerNoteEntityTransfer
     */
    public function addNote(SpyCustomerNoteEntityTransfer $customerNoteEntityTransfer): SpyCustomerNoteEntityTransfer;

    /**
     * Specification:
     * - Inserts a note from logged-in Zed user to the database
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SpyCustomerNoteEntityTransfer $customerNoteEntityTransfer
     *
     * @return \Generated\Shared\Transfer\SpyCustomerNoteEntityTransfer
     */
    public function addNoteFromCurrentUser(SpyCustomerNoteEntityTransfer $customerNoteEntityTransfer): SpyCustomerNoteEntityTransfer;

    /**
     * Specification:
     * - Fetches notes using repository by customer id
     *
     * @api
     *
     * @param int $idCustomer
     *
     * @return \Generated\Shared\Transfer\CustomerNoteCollectionTransfer
     */
    public function getNotes(int $idCustomer): CustomerNoteCollectionTransfer;

    /**
     * Specification:
     * - Fetches customer notes using repository by the provided criteria.
     * - Filters by `CustomerNoteConditions.customerIds` when set; returns every note otherwise.
     * - Applies `CustomerNoteCriteria.sortCollection`; a field outside the module's sortable field
     *   map is ignored rather than passed to the database.
     * - Orders by newest first when no sort is requested, and always breaks ties on the note id, so
     *   paging over the collection cannot repeat or skip a row.
     * - Applies `CustomerNoteCriteria.pagination` when set and returns it on the collection populated
     *   with the result metadata.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\CustomerNoteCollectionTransfer
     */
    public function getCustomerNoteCollection(
        CustomerNoteCriteriaTransfer $customerNoteCriteriaTransfer
    ): CustomerNoteCollectionTransfer;
}
