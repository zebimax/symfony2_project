<?php

namespace AppBundle\Tests\Unit\Security\Authorization\Voter;

use AppBundle\Entity\Role;
use AppBundle\Entity\User;
use AppBundle\Security\Authorization\Voter\ProfileRoleVoter;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

class ProfileRoleVoterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ProfileRoleVoter
     */
    protected $object;

    /**
     * @param $roleName
     * @param array $attributes
     * @param $expected
     * @dataProvider voteDataProvider
     * @covers AppBundle\Security\Authorization\Voter\ProfileRoleVoter::vote
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
        return [
            'access abstain not supported attribute' => [
                'roleName' => Role::OPERATOR,
                'attributes' => ['edit'],
                'expected' => VoterInterface::ACCESS_ABSTAIN,
            ],
            'home access granted operator' => [
                'roleName' => Role::OPERATOR,
                'attributes' => [ProfileRoleVoter::HOME],
                'expected' => VoterInterface::ACCESS_GRANTED,
            ],
            'home access granted manager' => [
                'roleName' => Role::MANAGER,
                'attributes' => [ProfileRoleVoter::HOME],
                'expected' => VoterInterface::ACCESS_GRANTED,
            ],
            'home access granted admin' => [
                'roleName' => Role::ADMINISTRATOR,
                'attributes' => [ProfileRoleVoter::HOME],
                'expected' => VoterInterface::ACCESS_GRANTED,
            ],
            'home access denied invalid role' => [
                'roleName' => 'ROLE_INVALID',
                'attributes' => [ProfileRoleVoter::HOME],
                'expected' => VoterInterface::ACCESS_DENIED,
            ],
            'profile access granted operator' => [
                'roleName' => Role::OPERATOR,
                'attributes' => [ProfileRoleVoter::PROFILE],
                'expected' => VoterInterface::ACCESS_GRANTED,
            ],
            'profile access granted manager' => [
                'roleName' => Role::MANAGER,
                'attributes' => [ProfileRoleVoter::PROFILE],
                'expected' => VoterInterface::ACCESS_GRANTED,
            ],
            'profile access granted admin' => [
                'roleName' => Role::ADMINISTRATOR,
                'attributes' => [ProfileRoleVoter::PROFILE],
                'expected' => VoterInterface::ACCESS_GRANTED,
            ],
            'profile access denied invalid role' => [
                'roleName' => 'ROLE_INVALID',
                'attributes' => [ProfileRoleVoter::PROFILE],
                'expected' => VoterInterface::ACCESS_DENIED,
            ],
            'profile_view access granted operator' => [
                'roleName' => Role::OPERATOR,
                'attributes' => [ProfileRoleVoter::PROFILE_VIEW],
                'expected' => VoterInterface::ACCESS_GRANTED,
            ],
            'profile_view access granted manager' => [
                'roleName' => Role::MANAGER,
                'attributes' => [ProfileRoleVoter::PROFILE_VIEW],
                'expected' => VoterInterface::ACCESS_GRANTED,
            ],
            'profile_view access granted admin' => [
                'roleName' => Role::ADMINISTRATOR,
                'attributes' => [ProfileRoleVoter::PROFILE_VIEW],
                'expected' => VoterInterface::ACCESS_GRANTED,
            ],
            'profile_view access denied invalid role' => [
                'roleName' => 'ROLE_INVALID',
                'attributes' => [ProfileRoleVoter::PROFILE_VIEW],
                'expected' => VoterInterface::ACCESS_DENIED,
            ],
            'profile_edit access granted operator' => [
                'roleName' => Role::OPERATOR,
                'attributes' => [ProfileRoleVoter::PROFILE_EDIT],
                'expected' => VoterInterface::ACCESS_GRANTED,
            ],
            'profile_edit access granted manager' => [
                'roleName' => Role::MANAGER,
                'attributes' => [ProfileRoleVoter::PROFILE_EDIT],
                'expected' => VoterInterface::ACCESS_GRANTED,
            ],
            'profile_edit access granted admin' => [
                'roleName' => Role::ADMINISTRATOR,
                'attributes' => [ProfileRoleVoter::PROFILE_EDIT],
                'expected' => VoterInterface::ACCESS_GRANTED,
            ],
            'profile_edit access denied invalid role' => [
                'roleName' => 'ROLE_INVALID',
                'attributes' => [ProfileRoleVoter::PROFILE_EDIT],
                'expected' => VoterInterface::ACCESS_DENIED,
            ],
        ];
    }

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
        $this->object = new ProfileRoleVoter($roleHierarchy);
    }
}
