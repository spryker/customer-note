<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CustomerNote\Business;

use Codeception\Test\Unit;
use Spryker\Zed\CustomerNote\Business\CustomerNoteFacade;

/**
 * Auto-generated group annotations
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
    const NOTES_COUNT = 10;

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
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->customerNoteFacade = new CustomerNoteFacade();
        $this->customerTransfer = $this->getCustomer();
        $this->userTransfer = $this->getUser();
    }

    /**
     * @return void
     */
    public function testAddNote()
    {
        $note = $this->customerNoteFacade->addNote($this->tester->getCustomerNoteTransfer(
            $this->userTransfer->getIdUser(),
            $this->customerTransfer->getIdCustomer()
        ));

        $this->assertTrue((bool)$note->getIdCustomerNote());
    }

    /**
     * @return void
     */
    public function testGetNotes()
    {
        $this->tester->hydrateCustomerNotesTableForCustomer(
            $this->userTransfer->getIdUser(),
            $this->customerTransfer->getIdCustomer(),
            static::NOTES_COUNT
        );
        $customerNotesCollectionTransfer = $this->customerNoteFacade->getNotes($this->customerTransfer->getIdCustomer());

        $this->assertSame(static::NOTES_COUNT, $customerNotesCollectionTransfer->getNotes()->count());
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function getCustomer()
    {
        return $this->tester->haveCustomer();
    }

    /**
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    protected function getUser()
    {
        return $this->tester->haveUser();
    }
}
