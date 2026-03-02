<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerNote\Business;

use Spryker\Zed\CustomerNote\Business\Model\NoteWriter;
use Spryker\Zed\CustomerNote\Business\Model\NoteWriterInterface;
use Spryker\Zed\CustomerNote\CustomerNoteDependencyProvider;
use Spryker\Zed\CustomerNote\Dependency\Facade\CustomerNoteToUserFacadeInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CustomerNote\Persistence\CustomerNoteEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\CustomerNote\Persistence\CustomerNoteRepositoryInterface getRepository()
 */
class CustomerNoteBusinessFactory extends AbstractBusinessFactory
{
    public function createNoteWriter(): NoteWriterInterface
    {
        return new NoteWriter(
            $this->getUserFacade(),
            $this->getEntityManager(),
        );
    }

    protected function getUserFacade(): CustomerNoteToUserFacadeInterface
    {
        return $this->getProvidedDependency(CustomerNoteDependencyProvider::FACADE_USER);
    }
}
