<?php

namespace AppBundle\Tests\Unit\Security\Authorization\Voter;

use AppBundle\Entity\User;
use AppBundle\Security\Authorization\Voter\UserVoter;
use AppBundle\Entity\Role;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

class UserVoterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var UserVoter
     */
    protected $object;

    protected function setUp()
    {
        $operatorRole = $this->getMock('AppBundle\Entity\Role');
        $operatorRole->expects($this->any())->method('getRole')->willReturn(Role::OPERATOR);
        $managerRole = $this->getMock('AppBundle\Entity\Role');
        $managerRole->expects($this->any())->method('getRole')->willReturn(Role::MANAGER);
        $adminRole = $this->getMock('AppBundle\Entity\Role');
        $adminRole->expects($this->any())->method('getRole')->willReturn(Role::ADMINISTRATOR);
        $invalidRole = $this->getMock('AppBundle\Entity\Role');
        $invalidRole->expects($this->any())->method('getRole')->willReturn('ROLE_INVALID');
        $roles = [
            Role::OPERATOR => [$operatorRole],
            Role::MANAGER => [$operatorRole, $managerRole],
            Role::ADMINISTRATOR => [$operatorRole, $managerRole, $adminRole],
            'ROLE_INVALID' => [$invalidRole],
        ];
        $roleHierarchy = $this->getMock('Symfony\Component\Security\Core\Role\RoleHierarchyInterface');
        $roleHierarchy
            ->expects($this->any())
            ->method('getReachableRoles')
            ->with($this->anything())
            ->will(
                $this->returnCallback(
                    function ($value) use ($roles) {
                        $role = $value[0];

                        return $roles[$role->getRole()];
                    }
                )
            );
        $this->object = new UserVoter($roleHierarchy);
    }

    /**
     * @covers       AppBundle\Security\Authorization\Voter\UserVoter::supportsAttribute
     * @dataProvider supportsAttributeDataProvider
     *
     * @param $attribute
     * @param $expected
     */
    public function testSupportsAttribute($attribute, $expected)
    {
        $this->assertEquals($expected, $this->object->supportsAttribute($attribute));
    }

    /**
     * @covers       AppBundle\Security\Authorization\Voter\UserVoter::supportsClass
     * @dataProvider supportsClassDataProvider
     *
     * @param $class
     * @param $expected
     */
    public function testSupportsClass($class, $expected)
    {
        $this->assertEquals($expected, $this->object->supportsClass($class));
    }

    /**
     * @covers AppBundle\Security\Authorization\Voter\UserVoter::vote
     *
     * @param $roleName
     * @param array $attributes
     * @param $object
     * @param $expected
     * @dataProvider voteDataProvider
     */
    public function testVote($roleName, $object, array $attributes, $expected)
    {
        $token = $this->getMockBuilder('Symfony\Component\Security\Core\Authentication\Token\TokenInterface')
            ->disableOriginalConstructor()->getMock();
        $role = (new Role())->setRole($roleName);
        $user = new User();
        $user->addRole($role);
        if ($expected !== VoterInterface::ACCESS_ABSTAIN) {
            $token->expects($this->once())->method('getUser')->willReturn($user);
        }
        if (is_callable($object)) {
            $object = $object($user);
        }
        $this->assertEquals($expected, $this->object->vote($token, $object, $attributes));
    }

    /**
     * supportsAttribute data provider.
     */
    public function supportsAttributeDataProvider()
    {
        return [
            'supports view attribute' => ['attribute' => 'view', 'expected' => true],
            'supports edit attribute' => ['attribute' => 'edit', 'expected' => true],
            'not supports add attribute' => ['attribute' => 'add', 'expected' => false],
        ];
    }

    /**
     * supportsClass data provider.
     */
    public function supportsClassDataProvider()
    {
        return [
            'supports AppBundle\Entity\User class' => ['class' => 'AppBundle\Entity\User', 'expected' => true],
            'not AppBundle\Entity\Project class' => ['class' => 'AppBundle\Entity\Project', 'expected' => false],
        ];
    }

    /**
     * Vote data provider.
     */
    public function voteDataProvider()
    {
        $data = [
            'access abstain not supported attribute' => [
                'roleName' => Role::OPERATOR,
                'object' => new User(),
                'attributes' => ['add'],
                'expected' => VoterInterface::ACCESS_ABSTAIN,
            ],
            'view access granted operator' => [
                'roleName' => Role::MANAGER,
                'object' => new User(),
                'attributes' => [UserVoter::VIEW],
                'expected' => VoterInterface::ACCESS_GRANTED,
            ],
            'view access granted manager' => [
                'roleName' => Role::MANAGER,
                'object' => new User(),
                'attributes' => [UserVoter::VIEW],
                'expected' => VoterInterface::ACCESS_GRANTED,
            ],
            'view access granted administrator' => [
                'roleName' => Role::ADMINISTRATOR,
                'object' => new User(),
                'attributes' => [UserVoter::VIEW],
                'expected' => VoterInterface::ACCESS_GRANTED,
            ],
            'edit access denied operator' => [
                'roleName' => Role::OPERATOR,
                'object' => new User(),
                'attributes' => [UserVoter::EDIT],
                'expected' => VoterInterface::ACCESS_DENIED,
            ],
            'edit access denied manager' => [
                'roleName' => Role::MANAGER,
                'object' => new User(),
                'attributes' => [UserVoter::EDIT],
                'expected' => VoterInterface::ACCESS_DENIED,
            ],
            'edit access granted administrator' => [
                'roleName' => Role::ADMINISTRATOR,
                'object' => new User(),
                'attributes' => [UserVoter::EDIT],
                'expected' => VoterInterface::ACCESS_GRANTED,
            ],
            'edit access granted operator owner' => [
                'roleName' => Role::OPERATOR,
                'object' => function ($user) {
                    return $user;
                },
                'attributes' => [UserVoter::EDIT],
                'expected' => VoterInterface::ACCESS_GRANTED,
            ],
        ];

        return $data;
    }
}
