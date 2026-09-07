<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CustomerNote\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CustomerNoteConditionsTransfer;
use Generated\Shared\Transfer\CustomerNoteCriteriaTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\SortTransfer;
use Generated\Shared\Transfer\SpyCustomerNoteEntityTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\CustomerNote\Business\CustomerNoteBusinessFactory;
use Spryker\Zed\CustomerNote\Business\CustomerNoteFacade;
use Spryker\Zed\CustomerNote\CustomerNoteDependencyProvider;
use Spryker\Zed\CustomerNote\Dependency\Facade\CustomerNoteToUserFacadeInterface;
use Spryker\Zed\Kernel\Container;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CustomerNote
 * @group Business
 * @group Facade
 * @group CustomerNoteFacadeTest
 * Add your own group annotations below this line
 */
class CustomerNoteFacadeTest extends Unit
{
    /**
     * @var int
     */
    public const NOTES_COUNT = 10;

    /**
     * @var \SprykerTest\Zed\CustomerNote\CustomerNoteBusinessTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\CustomerNote\Business\CustomerNoteFacadeInterface
     */
    protected $customerNoteFacade;

    /**
     * @var \Generated\Shared\Transfer\CustomerTransfer
     */
    protected $customerTransfer;

    /**
     * @var \Generated\Shared\Transfer\UserTransfer
     */
    protected $userTransfer;

    /**
     * @var \Spryker\Zed\Kernel\Container
     */
    protected $businessLayerDependencies;

    public function setUp(): void
    {
        parent::setUp();
        $this->customerNoteFacade = new CustomerNoteFacade();
        $this->customerTransfer = $this->getCustomer();
        $this->userTransfer = $this->getUser();
    }

    protected function getBusinessFactory(): CustomerNoteBusinessFactory
    {
        $customerNoteBusinessFactory = new CustomerNoteBusinessFactory();
        $customerNoteBusinessFactory->setContainer($this->getContainer());

        return $customerNoteBusinessFactory;
    }

    protected function getContainer(): Container
    {
        $dependencyProvider = new CustomerNoteDependencyProvider();
        $this->businessLayerDependencies = new Container();

        $dependencyProvider->provideBusinessLayerDependencies($this->businessLayerDependencies);

        $this->businessLayerDependencies[CustomerNoteDependencyProvider::FACADE_USER] =
            $this->getMockBuilder(CustomerNoteToUserFacadeInterface::class)->getMock()
            ->method('getCurrentUser')
            ->willReturn($this->userTransfer);

        return $this->businessLayerDependencies;
    }

    public function testAddNoteReturnsNotEmptyValueOnSuccess(): void
    {
        // Arrange
        $customerNoteEntityTransfer = $this->tester->getCustomerNoteTransfer(
            $this->userTransfer->getIdUser(),
            $this->customerTransfer->getIdCustomer(),
        );

        // Act
        $note = $this->customerNoteFacade->addNote($customerNoteEntityTransfer);

        // Assert
        $this->assertTrue((bool)$note->getIdCustomerNote());
    }

    public function testAddNoteFromCurrentUserReturnsNotEmptyValueOnSuccess(): void
    {
        // Arrange
        $customerNoteEntityTransfer = $this->tester->getCustomerNoteTransfer(
            $this->userTransfer->getIdUser(),
            $this->customerTransfer->getIdCustomer(),
        );

        // Act
        $note = $this->customerNoteFacade->addNote($customerNoteEntityTransfer);

        // Assert
        $this->assertTrue((bool)$note->getIdCustomerNote());
    }

    public function testGetNotesReturnsProperAmountOfNotes(): void
    {
        // Arrange
        $this->createCustomerNotesWithFkUserAndFkCustomer(
            $this->userTransfer->getIdUser(),
            $this->customerTransfer->getIdCustomer(),
            static::NOTES_COUNT,
        );

        // Act
        $customerNoteCollectionTransfer = $this->customerNoteFacade->getNotes($this->customerTransfer->getIdCustomer());

        // Assert
        $this->assertSame(static::NOTES_COUNT, $customerNoteCollectionTransfer->getNotes()->count());
    }

    public function testAddNoteReturnsTheBehaviourDerivedColumnsOnTheSavedNote(): void
    {
        // Act
        $customerNoteEntityTransfer = $this->customerNoteFacade->addNote($this->tester->getCustomerNoteTransfer(
            $this->userTransfer->getIdUser(),
            $this->customerTransfer->getIdCustomer(),
        ));

        // Assert: the Backend API builds its POST response straight from this transfer, so the values
        // the uuid and timestampable behaviours fill in on insert have to come back on it.
        $this->assertNotEmpty(
            $customerNoteEntityTransfer->getUuid(),
            'The uuid behaviour must fill the public identifier on insert, as the API addresses notes by it.',
        );
        $this->assertNotEmpty(
            $customerNoteEntityTransfer->getCreatedAt(),
            'The timestampable behaviour must fill createdAt on insert, as the API reports it on the created note.',
        );
    }

    public function testGetCustomerNoteCollectionReturnsOnlyTheNotesOfTheRequestedCustomer(): void
    {
        // Arrange
        $otherCustomerTransfer = $this->tester->haveCustomer();

        $this->createCustomerNotesWithFkUserAndFkCustomer(
            $this->userTransfer->getIdUser(),
            $this->customerTransfer->getIdCustomer(),
            2,
        );
        $this->createCustomerNotesWithFkUserAndFkCustomer(
            $this->userTransfer->getIdUser(),
            $otherCustomerTransfer->getIdCustomer(),
            3,
        );

        // Act
        $customerNoteCollectionTransfer = $this->customerNoteFacade->getCustomerNoteCollection(
            $this->createCriteria($this->customerTransfer->getIdCustomer()),
        );

        // Assert
        $this->assertSame(2, $customerNoteCollectionTransfer->getNotes()->count());

        foreach ($customerNoteCollectionTransfer->getNotes() as $customerNoteEntityTransfer) {
            $this->assertSame(
                $this->customerTransfer->getIdCustomer(),
                $customerNoteEntityTransfer->getFkCustomer(),
                'A note belonging to another customer must never appear in the collection.',
            );
        }
    }

    public function testGetCustomerNoteCollectionReturnsEmptyCollectionForCustomerWithoutNotes(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();

        // Act
        $customerNoteCollectionTransfer = $this->customerNoteFacade->getCustomerNoteCollection(
            $this->createCriteria($customerTransfer->getIdCustomer()),
        );

        // Assert
        $this->assertSame(0, $customerNoteCollectionTransfer->getNotes()->count());
        $this->assertSame(0, $customerNoteCollectionTransfer->getPagination()->getNbResults());
    }

    public function testGetCustomerNoteCollectionAppliesThePageWindowAndReportsTheResultMetadata(): void
    {
        // Arrange
        $this->createCustomerNotesWithFkUserAndFkCustomer(
            $this->userTransfer->getIdUser(),
            $this->customerTransfer->getIdCustomer(),
            static::NOTES_COUNT,
        );

        // Act
        $customerNoteCollectionTransfer = $this->customerNoteFacade->getCustomerNoteCollection(
            $this->createCriteria($this->customerTransfer->getIdCustomer(), 2, 4),
        );

        // Assert
        $paginationTransfer = $customerNoteCollectionTransfer->getPagination();

        $this->assertSame(4, $customerNoteCollectionTransfer->getNotes()->count());
        $this->assertSame(static::NOTES_COUNT, $paginationTransfer->getNbResults());
        $this->assertSame(2, $paginationTransfer->getPage());
        $this->assertSame(3, $paginationTransfer->getLastPage());
    }

    public function testGetCustomerNoteCollectionPagesWithoutRepeatingOrSkippingANote(): void
    {
        // Arrange: every note is written within the same second, so `created_at` alone cannot order them.
        $this->createCustomerNotesWithFkUserAndFkCustomer(
            $this->userTransfer->getIdUser(),
            $this->customerTransfer->getIdCustomer(),
            static::NOTES_COUNT,
        );

        // Act
        $pagedIds = [];

        for ($page = 1; $page <= 5; $page++) {
            $customerNoteCollectionTransfer = $this->customerNoteFacade->getCustomerNoteCollection(
                $this->createCriteria($this->customerTransfer->getIdCustomer(), $page, 2),
            );

            foreach ($customerNoteCollectionTransfer->getNotes() as $customerNoteEntityTransfer) {
                $pagedIds[] = $customerNoteEntityTransfer->getIdCustomerNote();
            }
        }

        // Assert
        $this->assertCount(static::NOTES_COUNT, $pagedIds);
        $this->assertCount(
            static::NOTES_COUNT,
            array_unique($pagedIds),
            'Paging over the collection must not repeat a note, which requires a total order in the query.',
        );
    }

    public function testGetCustomerNoteCollectionSortsByAWhitelistedFieldInTheRequestedDirection(): void
    {
        // Arrange
        $idCustomer = $this->customerTransfer->getIdCustomer();

        foreach (['author-c', 'author-a', 'author-b'] as $username) {
            $this->customerNoteFacade->addNote(
                $this->tester->getCustomerNoteTransfer($this->userTransfer->getIdUser(), $idCustomer)
                    ->setUsername($username),
            );
        }

        // Act
        $customerNoteCollectionTransfer = $this->customerNoteFacade->getCustomerNoteCollection(
            $this->createCriteria($idCustomer)->addSort(
                (new SortTransfer())
                    ->setField(SpyCustomerNoteEntityTransfer::USERNAME)
                    ->setIsAscending(true),
            ),
        );

        // Assert
        $usernames = [];

        foreach ($customerNoteCollectionTransfer->getNotes() as $customerNoteEntityTransfer) {
            $usernames[] = $customerNoteEntityTransfer->getUsername();
        }

        $this->assertSame(['author-a', 'author-b', 'author-c'], $usernames);
    }

    public function testGetCustomerNoteCollectionBreaksATimestampTieInTheRequestedDirection(): void
    {
        // Arrange: notes written in the same second, so `created_at` alone cannot order them.
        $idCustomer = $this->customerTransfer->getIdCustomer();
        $this->createCustomerNotesWithFkUserAndFkCustomer($this->userTransfer->getIdUser(), $idCustomer, 3);

        // Act
        $ascendingIds = $this->grabNoteIds($idCustomer, true);
        $descendingIds = $this->grabNoteIds($idCustomer, false);

        // Assert
        $this->assertSame(
            array_reverse($ascendingIds),
            $descendingIds,
            'An ascending sort must be the exact reverse of a descending one, even when every timestamp ties.',
        );
        $this->assertSame(
            $ascendingIds,
            array_values(array_unique($ascendingIds)),
            'The tiebreaker must produce a total order so paging cannot repeat a note.',
        );
    }

    /**
     * @return array<int, int|null>
     */
    protected function grabNoteIds(int $idCustomer, bool $isAscending): array
    {
        $customerNoteCollectionTransfer = $this->customerNoteFacade->getCustomerNoteCollection(
            $this->createCriteria($idCustomer)->addSort(
                (new SortTransfer())
                    ->setField(SpyCustomerNoteEntityTransfer::CREATED_AT)
                    ->setIsAscending($isAscending),
            ),
        );

        $ids = [];

        foreach ($customerNoteCollectionTransfer->getNotes() as $customerNoteEntityTransfer) {
            $ids[] = $customerNoteEntityTransfer->getIdCustomerNote();
        }

        return $ids;
    }

    public function testGetCustomerNoteCollectionIgnoresASortFieldOutsideTheAllowList(): void
    {
        // Arrange
        $this->createCustomerNotesWithFkUserAndFkCustomer(
            $this->userTransfer->getIdUser(),
            $this->customerTransfer->getIdCustomer(),
            2,
        );

        // Act
        $customerNoteCollectionTransfer = $this->customerNoteFacade->getCustomerNoteCollection(
            $this->createCriteria($this->customerTransfer->getIdCustomer())->addSort(
                (new SortTransfer())->setField('message; DROP TABLE spy_customer_note; --')->setIsAscending(true),
            ),
        );

        // Assert
        $this->assertSame(
            2,
            $customerNoteCollectionTransfer->getNotes()->count(),
            'An unmapped sort field must be dropped, never interpolated into the ORDER BY clause.',
        );
    }

    protected function createCriteria(int $idCustomer, int $page = 1, int $maxPerPage = 10): CustomerNoteCriteriaTransfer
    {
        return (new CustomerNoteCriteriaTransfer())
            ->setCustomerNoteConditions(
                (new CustomerNoteConditionsTransfer())->addIdCustomer($idCustomer),
            )
            ->setPagination(
                (new PaginationTransfer())->setPage($page)->setMaxPerPage($maxPerPage),
            );
    }

    protected function getCustomer(): CustomerTransfer
    {
        return $this->tester->haveCustomer();
    }

    protected function getUser(): UserTransfer
    {
        return $this->tester->haveUser();
    }

    protected function createCustomerNotesWithFkUserAndFkCustomer(int $fkUser, int $fkCustomer, int $number): void
    {
        for ($i = 0; $i < $number; $i++) {
            $this->customerNoteFacade->addNote(
                $this->tester->getCustomerNoteTransfer($fkUser, $fkCustomer),
            );
        }
    }
}
