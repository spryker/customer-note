<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerNote\Persistence;

use ArrayObject;
use Generated\Shared\Transfer\CustomerNoteCollectionTransfer;
use Generated\Shared\Transfer\CustomerNoteCriteriaTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Orm\Zed\CustomerNote\Persistence\Map\SpyCustomerNoteTableMap;
use Orm\Zed\CustomerNote\Persistence\SpyCustomerNoteQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\CustomerNote\Persistence\CustomerNotePersistenceFactory getFactory()
 */
class CustomerNoteRepository extends AbstractRepository implements CustomerNoteRepositoryInterface
{
    protected const string NOTE_UUID_FILTER_METHOD = 'filterByUuid_In';

    /**
     * {@inheritDoc}
     *
     * @param int $idCustomer
     *
     * @return \Generated\Shared\Transfer\CustomerNoteCollectionTransfer
     */
    public function getCustomerNoteCollectionByIdCustomer(int $idCustomer): CustomerNoteCollectionTransfer
    {
        $customerNoteQuery = $this->getFactory()->createCustomerNoteQuery();
        $customerNoteQuery->filterByFkCustomer($idCustomer);
        $customerNoteEntityTransfers = $this->buildQueryFromCriteria($customerNoteQuery)->find();

        return $this->prepareCustomerNoteCollectionTransfer($customerNoteEntityTransfers);
    }

    public function getCustomerNoteCollection(
        CustomerNoteCriteriaTransfer $customerNoteCriteriaTransfer
    ): CustomerNoteCollectionTransfer {
        $paginationTransfer = $customerNoteCriteriaTransfer->getPagination();

        $customerNoteQuery = $this->buildCustomerNoteQueryByConditions($customerNoteCriteriaTransfer);
        $customerNoteQuery = $this->applyCustomerNoteSortToQuery(
            $customerNoteQuery,
            $customerNoteCriteriaTransfer->getSortCollection(),
        );
        $customerNoteQuery = $this->applyCustomerNotePagination($customerNoteQuery, $paginationTransfer);

        $customerNoteEntityTransfers = $this->buildQueryFromCriteria($customerNoteQuery)->find();

        return $this->prepareCustomerNoteCollectionTransfer($customerNoteEntityTransfers)
            ->setPagination($paginationTransfer);
    }

    protected function buildCustomerNoteQueryByConditions(
        CustomerNoteCriteriaTransfer $customerNoteCriteriaTransfer
    ): SpyCustomerNoteQuery {
        $customerNoteQuery = $this->getFactory()->createCustomerNoteQuery();

        $customerNoteConditionsTransfer = $customerNoteCriteriaTransfer->getCustomerNoteConditions();

        if ($customerNoteConditionsTransfer === null) {
            return $customerNoteQuery;
        }

        if ($customerNoteConditionsTransfer->getUuids() && method_exists($customerNoteQuery, static::NOTE_UUID_FILTER_METHOD)) {
            $customerNoteQuery->filterByUuid_In($customerNoteConditionsTransfer->getUuids());
        }

        if ($customerNoteConditionsTransfer->getCustomerIds()) {
            $customerNoteQuery->filterByFkCustomer_In($customerNoteConditionsTransfer->getCustomerIds());
        }

        return $customerNoteQuery;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\SortTransfer> $sortCollection
     */
    protected function applyCustomerNoteSortToQuery(
        SpyCustomerNoteQuery $customerNoteQuery,
        ArrayObject $sortCollection
    ): SpyCustomerNoteQuery {
        $sortableFieldMap = $this->getFactory()->getConfig()->getCustomerNoteCollectionSortableFieldMap();
        $tiebreakerDirection = Criteria::DESC;
        $hasAppliedSort = false;

        foreach ($sortCollection as $sortTransfer) {
            $column = $sortableFieldMap[$sortTransfer->getField()] ?? null;

            if ($column === null) {
                continue;
            }

            $direction = $sortTransfer->getIsAscending() === false ? Criteria::DESC : Criteria::ASC;
            $customerNoteQuery->orderBy($column, $direction);
            $tiebreakerDirection = $direction;
            $hasAppliedSort = true;
        }

        if (!$hasAppliedSort) {
            $customerNoteQuery->orderBy(SpyCustomerNoteTableMap::COL_CREATED_AT, Criteria::DESC);
        }

        return $customerNoteQuery->orderBy(SpyCustomerNoteTableMap::COL_ID_CUSTOMER_NOTE, $tiebreakerDirection);
    }

    protected function applyCustomerNotePagination(
        SpyCustomerNoteQuery $customerNoteQuery,
        ?PaginationTransfer $paginationTransfer = null
    ): SpyCustomerNoteQuery {
        if (!$paginationTransfer) {
            return $customerNoteQuery;
        }

        $paginationModel = $customerNoteQuery->paginate(
            $paginationTransfer->requirePage()->getPage(),
            $paginationTransfer->requireMaxPerPage()->getMaxPerPage(),
        );

        $paginationTransfer->setNbResults($paginationModel->getNbResults());
        $paginationTransfer->setFirstIndex($paginationModel->getFirstIndex());
        $paginationTransfer->setLastIndex($paginationModel->getLastIndex());
        $paginationTransfer->setFirstPage($paginationModel->getFirstPage());
        $paginationTransfer->setLastPage($paginationModel->getLastPage());
        $paginationTransfer->setNextPage($paginationModel->getNextPage());
        $paginationTransfer->setPreviousPage($paginationModel->getPreviousPage());

        /** @var \Orm\Zed\CustomerNote\Persistence\SpyCustomerNoteQuery $paginatedCustomerNoteQuery */
        $paginatedCustomerNoteQuery = $paginationModel->getQuery();

        return $paginatedCustomerNoteQuery;
    }

    /**
     * @param array<\Generated\Shared\Transfer\SpyCustomerNoteEntityTransfer> $customerNoteEntityTransfers
     *
     * @return \Generated\Shared\Transfer\CustomerNoteCollectionTransfer
     */
    protected function prepareCustomerNoteCollectionTransfer(array $customerNoteEntityTransfers): CustomerNoteCollectionTransfer
    {
        $notesCollection = new ArrayObject($customerNoteEntityTransfers);
        $collectionTransfer = new CustomerNoteCollectionTransfer();
        $collectionTransfer->setNotes($notesCollection);

        return $collectionTransfer;
    }
}
