<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\CustomerNote\Helper;

use Codeception\Module;
use Generated\Shared\Transfer\SpyCustomerNoteEntityTransfer;
use Orm\Zed\CustomerNote\Persistence\SpyCustomerNote;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class CustomerNoteDataHelper extends Module
{
    use LocatorHelperTrait;

    const TEST_NOTE_AUTHOR = 'test';
    const TEST_NOTE_MESSAGE = 'test';

    /**
     * @return \Spryker\Zed\CustomerNote\Business\CustomerNoteFacadeInterface
     */
    protected function getCustomerNoteFacade()
    {
        $this->getLocator()->customerNote()->facade();
    }

    /**
     * @param int $fkUser
     * @param int $fkCustomer
     *
     * @return \Generated\Shared\Transfer\SpyCustomerNoteEntityTransfer
     */
    public function getCustomerNoteTransfer(int $fkUser, int $fkCustomer): SpyCustomerNoteEntityTransfer
    {
        $noteTransfer = new SpyCustomerNoteEntityTransfer();
        $noteTransfer->setMessage(static::TEST_NOTE_MESSAGE);
        $noteTransfer->setUsername(static::TEST_NOTE_AUTHOR);
        $noteTransfer->setFkCustomer($fkCustomer);
        $noteTransfer->setFkUser($fkUser);

        return $noteTransfer;
    }

    /**
     * @param int $fkUser
     * @param int $fkCustomer
     * @param int $number
     *
     * @return void
     */
    public function hydrateCustomerNotesTableForCustomer(int $fkUser, int $fkCustomer, int $number)
    {
        for ($i = 0; $i < $number; $i++) {
            $noteTransfer = $this->getCustomerNoteTransfer($fkUser, $fkCustomer);
            $noteEntity = new SpyCustomerNote();
            $noteEntity->fromArray($noteTransfer->toArray());
            $noteEntity->save();
        }
    }
}
