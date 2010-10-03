<?php
/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the LGPL. For more information, see
 * <http://www.doctrine-project.org>.
 */

namespace Doctrine\Tests\ORM\DataFixtures;

require_once __DIR__.'/TestInit.php';

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\DataFixtures\Executer;
use PHPUnit_Framework_TestCase;

/**
 * Test Fixture executer.
 *
 * @author Jonathan H. Wage <jonwage@gmail.com>
 */
class ExecuterTest extends BaseTest
{
    public function testExecuteWithNoPurge()
    {
        $em = $this->getMockEntityManager();
        $purger = $this->getMockPurger();
        $purger->expects($this->once())
            ->method('setEntityManager')
            ->with($em);
        $executer = new Executer($em, $purger);
        $fixture = $this->getMockFixture($em);
        $fixture->expects($this->once())
            ->method('load')
            ->with($em);
        $executer->execute(array($fixture), true);
    }

    public function testExecuteWithPurge()
    {
        $em = $this->getMockEntityManager();
        $purger = $this->getMockPurger();
        $purger->expects($this->once())
            ->method('purge')
            ->will($this->returnValue(null));
        $executer = new Executer($em, $purger);
        $fixture = $this->getMockFixture($em);
        $fixture->expects($this->once())
            ->method('load')
            ->with($em);
        $executer->execute(array($fixture), false);
    }

    public function testExecuteTransaction()
    {
        $em = $this->getMockEntityManager();
        $executer = new Executer($em);
        $fixture = $this->getMockFixture($em);
        $executer->execute(array($fixture), true);
    }

    private function getMockFixture($em)
    {
        return $this->getMock('Doctrine\ORM\DataFixtures\Fixture');
    }

    private function getMockPurger()
    {
        return $this->getMock('Doctrine\ORM\DataFixtures\Purger');
    }
}