<?php

namespace AppBundle\Tests\Unit\Security\Authorization\Voter;

use AppBundle\Entity\Role;
use AppBundle\Entity\User;
use AppBundle\Security\Authorization\Voter\IssuesRoleVoter;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

class IssuesRoleVoterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var IssuesRoleVoter
     */
    protected $object;

    /**
     * @var array
     */
    protected $supportedAttributes = [
        IssuesRoleVoter::ISSUES,
        IssuesRoleVoter::ISSUES_LIST,
        IssuesRoleVoter::ISSUES_ADD,
    ];

    /**
     * @param $roleName
     * @param array $attributes
     * @param $expected
     * @dataProvider voteDataProvider
     * @covers AppBundle\Security\Authorization\Voter\IssuesRoleVoter::vote
     */
    public function testVote($roleName, array $attributes, $expected)
    {
        $token = $this->getMockBuilder('Symfony\Component\Security\Core\Authentication\Token\TokenInterface')
            ->disableOriginalConstructor()->getMock();
        $role = (new Role())->setRole($roleName);
        $user = new User();
        $user->addRole($role);
        if ($expected !== VoterInterface::ACCESS_ABSTAIN) {
            $token->expects($this->once())->method('getUser')->willReturn($user);
        }
        $this->assertEquals($expected, $this->object->vote($token, null, $attributes));
    }

    /**
     * Vote data provider.
     *
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function voteDataProvider()
    {
        $data = [
            'access abstain not supported attribute' => [
                'roleName' => Role::OPERATOR,
                'attributes' => ['edit'],
                'expected' => VoterInterface::ACCESS_ABSTAIN,
            ],
        ];
        foreach ($this->supportedAttributes as $attribute) {
            $data[$attribute.' access granted operator'] = [
                'roleName' => Role::OPERATOR,
                'attributes' => [$attribute],
                'expected' => VoterInterface::ACCESS_GRANTED,
            ];
            $data[$attribute.' access granted manager'] = [
                'roleName' => Role::MANAGER,
                'attributes' => [$attribute],
                'expected' => VoterInterface::ACCESS_GRANTED,
            ];
            $data[$attribute.' access granted administrator'] = [
                'roleName' => Role::ADMINISTRATOR,
                'attributes' => [$attribute],
                'expected' => VoterInterface::ACCESS_GRANTED,
            ];
            $data[$attribute.' access denied invalid role'] = [
                'roleName' => 'ROLE_INVALID',
                'attributes' => [$attribute],
                'expected' => VoterInterface::ACCESS_DENIED,
            ];
        }

        return $data;
    }

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
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
        $this->object = new IssuesRoleVoter($roleHierarchy);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }
}
