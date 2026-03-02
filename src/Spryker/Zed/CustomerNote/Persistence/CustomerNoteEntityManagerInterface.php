<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerNote\Persistence;

use Generated\Shared\Transfer\SpyCustomerNoteEntityTransfer;

interface CustomerNoteEntityManagerInterface
{
    public function saveNote(SpyCustomerNoteEntityTransfer $customerNoteEntityTransfer): SpyCustomerNoteEntityTransfer;
}
