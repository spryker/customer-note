<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace SprykerTest\Zed\CustomerNote\Persistence;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CustomerNoteConditionsTransfer;
use Generated\Shared\Transfer\CustomerNoteCriteriaTransfer;
use Orm\Zed\CustomerNote\Persistence\Map\SpyCustomerNoteTableMap;
use Orm\Zed\CustomerNote\Persistence\SpyCustomerNoteQuery;
use Spryker\Zed\CustomerNote\Persistence\CustomerNotePersistenceFactory;
use SprykerTest\Zed\CustomerNote\Persistence\Fixtures\CustomerNoteUuidGuardProbe;
use SprykerTest\Zed\CustomerNote\Persistence\Fixtures\CustomerNoteUuidGuardProbeWithoutUuidColumn;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CustomerNote
 * @group Persistence
 * @group CustomerNoteRepositoryUuidGuardTest
 * Add your own group annotations below this line
 */
class CustomerNoteRepositoryUuidGuardTest extends Unit
{
    protected const string UUID = '5caa05f5-41f5-5e6c-a254-07d7887fb4e9';

    protected const int ID_CUSTOMER = 1;

    public function testKeepsTheOtherConditionsWhenTheUuidConditionCannotBeApplied(): void
    {
        // Arrange: a uuid the query cannot filter on must not cost the customer scope as well.
        $customerNoteUuidGuardProbe = $this->createProbeWithoutUuidColumn();

        $customerNoteCriteriaTransfer = (new CustomerNoteCriteriaTransfer())
            ->setCustomerNoteConditions(
                (new CustomerNoteConditionsTransfer())
                    ->addUuid(static::UUID)
                    ->addIdCustomer(static::ID_CUSTOMER),
            );

        // Act
        $customerNoteQuery = $customerNoteUuidGuardProbe->exposeBuildCustomerNoteQueryByConditions(
            $customerNoteCriteriaTransfer,
        );

        // Assert
        $params = [];

        $this->assertStringContainsString(
            SpyCustomerNoteTableMap::COL_FK_CUSTOMER,
            $customerNoteQuery->createSelectSql($params),
            'The customer condition must survive a uuid condition that cannot be applied.',
        );
    }

    public function testBuildsTheQueryWhenTheUuidConditionCanBeApplied(): void
    {
        // Arrange
        $customerNoteUuidGuardProbe = $this->createProbe();

        // Act
        $customerNoteQuery = $customerNoteUuidGuardProbe->exposeBuildCustomerNoteQueryByConditions(
            $this->createCriteriaWithUuid(),
        );

        // Assert
        $this->assertInstanceOf(
            SpyCustomerNoteQuery::class,
            $customerNoteQuery,
            'This suite runs with the note uuid schema extension installed, so the filter must be usable.',
        );
    }

    public function testBuildsTheQueryForNonUuidConditionsWithoutTheUuidColumn(): void
    {
        // Arrange
        $customerNoteUuidGuardProbe = $this->createProbeWithoutUuidColumn();

        $customerNoteCriteriaTransfer = (new CustomerNoteCriteriaTransfer())
            ->setCustomerNoteConditions(
                (new CustomerNoteConditionsTransfer())->addIdCustomer(static::ID_CUSTOMER),
            );

        // Act
        $customerNoteQuery = $customerNoteUuidGuardProbe->exposeBuildCustomerNoteQueryByConditions(
            $customerNoteCriteriaTransfer,
        );

        // Assert
        $this->assertInstanceOf(SpyCustomerNoteQuery::class, $customerNoteQuery);
    }

    public function testBuildsTheQueryWhenNoConditionsAreSet(): void
    {
        // Arrange
        $customerNoteUuidGuardProbe = $this->createProbeWithoutUuidColumn();

        // Act
        $customerNoteQuery = $customerNoteUuidGuardProbe->exposeBuildCustomerNoteQueryByConditions(
            new CustomerNoteCriteriaTransfer(),
        );

        // Assert
        $this->assertInstanceOf(SpyCustomerNoteQuery::class, $customerNoteQuery);
    }

    protected function createCriteriaWithUuid(): CustomerNoteCriteriaTransfer
    {
        return (new CustomerNoteCriteriaTransfer())
            ->setCustomerNoteConditions((new CustomerNoteConditionsTransfer())->addUuid(static::UUID));
    }

    protected function createProbe(): CustomerNoteUuidGuardProbe
    {
        /** @var \SprykerTest\Zed\CustomerNote\Persistence\Fixtures\CustomerNoteUuidGuardProbe $customerNoteUuidGuardProbe */
        $customerNoteUuidGuardProbe = (new CustomerNoteUuidGuardProbe())
            ->setFactory(new CustomerNotePersistenceFactory());

        return $customerNoteUuidGuardProbe;
    }

    protected function createProbeWithoutUuidColumn(): CustomerNoteUuidGuardProbeWithoutUuidColumn
    {
        /** @var \SprykerTest\Zed\CustomerNote\Persistence\Fixtures\CustomerNoteUuidGuardProbeWithoutUuidColumn $customerNoteUuidGuardProbe */
        $customerNoteUuidGuardProbe = (new CustomerNoteUuidGuardProbeWithoutUuidColumn())
            ->setFactory(new CustomerNotePersistenceFactory());

        return $customerNoteUuidGuardProbe;
    }
}
