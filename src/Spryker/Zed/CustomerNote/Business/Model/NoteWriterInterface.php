<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerNote\Business\Model;

use Generated\Shared\Transfer\SpyCustomerNoteEntityTransfer;

interface NoteWriterInterface
{
    public function createCustomerNote(SpyCustomerNoteEntityTransfer $customerNoteEntityTransfer): SpyCustomerNoteEntityTransfer;
}
