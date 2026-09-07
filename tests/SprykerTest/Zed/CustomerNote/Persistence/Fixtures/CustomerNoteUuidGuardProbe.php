<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace SprykerTest\Zed\CustomerNote\Persistence\Fixtures;

use Generated\Shared\Transfer\CustomerNoteCriteriaTransfer;
use Orm\Zed\CustomerNote\Persistence\SpyCustomerNoteQuery;
use Spryker\Zed\CustomerNote\Persistence\CustomerNoteRepository;

class CustomerNoteUuidGuardProbe extends CustomerNoteRepository
{
    public function exposeBuildCustomerNoteQueryByConditions(
        CustomerNoteCriteriaTransfer $customerNoteCriteriaTransfer
    ): SpyCustomerNoteQuery {
        return $this->buildCustomerNoteQueryByConditions($customerNoteCriteriaTransfer);
    }
}
