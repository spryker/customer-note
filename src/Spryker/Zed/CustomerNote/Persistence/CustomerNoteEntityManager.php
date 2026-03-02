<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerNote\Persistence;

use Generated\Shared\Transfer\SpyCustomerNoteEntityTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Generated\Shared\Transfer\SpyCustomerNoteEntityTransfer save(\Generated\Shared\Transfer\SpyCustomerNoteEntityTransfer $customerNoteEntityTransfer)
 * @method \Spryker\Zed\CustomerNote\Persistence\CustomerNotePersistenceFactory getFactory()
 */
class CustomerNoteEntityManager extends AbstractEntityManager implements CustomerNoteEntityManagerInterface
{
    public function saveNote(SpyCustomerNoteEntityTransfer $customerNoteEntityTransfer): SpyCustomerNoteEntityTransfer
    {
        return $this->save($customerNoteEntityTransfer);
    }
}
