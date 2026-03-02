<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerNote\Business\Model;

use Generated\Shared\Transfer\SpyCustomerNoteEntityTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\CustomerNote\Dependency\Facade\CustomerNoteToUserFacadeInterface;
use Spryker\Zed\CustomerNote\Persistence\CustomerNoteEntityManagerInterface;

class NoteWriter implements NoteWriterInterface
{
    /**
     * @var string
     */
    protected const USERNAME_FORMAT = '%s %s';

    /**
     * @var \Spryker\Zed\CustomerNote\Dependency\Facade\CustomerNoteToUserFacadeInterface
     */
    protected $userFacade;

    /**
     * @var \Spryker\Zed\CustomerNote\Persistence\CustomerNoteEntityManagerInterface
     */
    protected $customerNoteEntityManager;

    public function __construct(CustomerNoteToUserFacadeInterface $userFacade, CustomerNoteEntityManagerInterface $customerNoteEntityManager)
    {
        $this->userFacade = $userFacade;
        $this->customerNoteEntityManager = $customerNoteEntityManager;
    }

    public function createCustomerNote(SpyCustomerNoteEntityTransfer $customerNoteEntityTransfer): SpyCustomerNoteEntityTransfer
    {
        $noteTransfer = $this->hydrateSpyCustomerNoteEntityTransfer($customerNoteEntityTransfer);

        return $this->customerNoteEntityManager->saveNote($noteTransfer);
    }

    protected function hydrateSpyCustomerNoteEntityTransfer(SpyCustomerNoteEntityTransfer $customerNoteEntityTransfer): SpyCustomerNoteEntityTransfer
    {
        $currentUserTransfer = $this->userFacade->getCurrentUser();
        $customerNoteEntityTransfer->setUsername($this->formatCommenterUsername($currentUserTransfer));
        $customerNoteEntityTransfer->setFkUser($currentUserTransfer->getIdUser());

        return $customerNoteEntityTransfer;
    }

    protected function formatCommenterUsername(UserTransfer $userTransfer): string
    {
        return sprintf(
            static::USERNAME_FORMAT,
            $userTransfer->getFirstName(),
            $userTransfer->getLastName(),
        );
    }
}
