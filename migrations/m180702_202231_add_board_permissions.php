<?php

use yeesoft\db\PermissionsMigration;

class m180702_202231_add_board_permissions extends PermissionsMigration
{

    public function beforeUp()
    {
        $this->addPermissionsGroup('boardManagement', 'Board Management');
    }

    public function afterDown()
    {
        $this->deletePermissionsGroup('boardManagement');
    }

    public function getPermissions()
    {
        return [
            'boardManagement' => [
                'links' => [
                    '/admin/board/*',
                    '/admin/board/default/*',
                    '/admin/board/group/*',
                    '/admin/board/data/*',
                ],
				
				/////////////////////////////
				// board list, board group 
				
                'viewBoards' => [
                    'title' => 'View Boards',
                    'links' => [
                        '/admin/board/default/index',
                        '/admin/board/default/view',
                        '/admin/board/default/grid-sort',
                        '/admin/board/default/grid-page-size',
						'/admin/board/group/index',
                        '/admin/board/group/view',
                    ],
                    'roles' => [
                        self::ROLE_ADMIN,
                    ],
                ],
                'editBoards' => [
                    'title' => 'Edit Boards',
                    'links' => [
                        '/admin/board/default/update',
                        '/admin/board/default/bulk-action',
						'/admin/board/default/bulk-activate',
                        '/admin/board/default/bulk-deactivate',
                        '/admin/board/default/toggle-attribute',
						'/admin/board/group/update',
                        '/admin/board/group/bulk-action',						
                    ],
                    'roles' => [
                        self::ROLE_ADMIN,
                    ],
                    'childs' => [
                        'viewBoards',
                    ],
                ],
                'createBoards' => [
                    'title' => 'Create Boards',
                    'links' => [
                        '/admin/board/default/create',
                        '/admin/board/group/create',
                    ],
                    'roles' => [
                        self::ROLE_ADMIN,
                    ],
                    'childs' => [
                        'viewBoards',
                    ],
                ],
                'deleteBoards' => [
                    'title' => 'Delete Boards',
                    'links' => [
                        '/admin/board/default/delete',
                        '/admin/board/default/bulk-delete',
						'/admin/board/group/delete',
                        '/admin/board/group/bulk-delete',
                    ],
                    'roles' => [
                        self::ROLE_ADMIN,
                    ],
                    'childs' => [
                        'viewBoards',
                    ],
                ],
                'fullBoardAccess' => [
                    'title' => 'Full Board Access',
                    'roles' => [
                        self::ROLE_MODERATOR,
                    ],
                ],
				
				/////////////////////////////
				// board data 

                'viewBoardDataAdmin' => [
                    'title' => 'View Boards',
                    'links' => [
                        '/admin/board/data/index',
                        '/admin/board/data/grid-sort',
                        '/admin/board/data/grid-page-size',
                    ],
                    'roles' => [
                        self::ROLE_AUTHOR,
                    ],
                    'childs' => [
                        'viewBoards',
                    ],
                ],
                'editBoardDataAdmin' => [
                    'title' => 'Edit Board Data',
                    'links' => [
                        '/admin/board/data/update',
                        '/admin/board/data/toggle-attribute',
                    ],
                    'roles' => [
                        self::ROLE_MODERATOR,
                    ],
                    'childs' => [
                        'viewBoards',
                    ],
                ],
                'createBoardDataAdmin' => [
                    'title' => 'Create Board Categories',
                    'links' => [
                        '/admin/board/data/create',
                    ],
                    'roles' => [
                        self::ROLE_MODERATOR,
                    ],
                    'childs' => [
                        'viewBoards',
                    ],
                ],
                'deleteBoardDataAdmin' => [
                    'title' => 'Delete Board Data',
                    'links' => [
                        '/admin/board/data/delete',
                        '/admin/board/data/bulk-delete',
                    ],
                    'roles' => [
                        self::ROLE_ADMIN,
                    ],
                    'childs' => [
                        'viewBoards',
                    ],
                ],
                'fullBoardDataAccessAdmin' => [
                    'title' => 'Full Board Data Access',
                    'roles' => [
                        self::ROLE_MODERATOR,
                    ],
                ],
            ],
        ];
    }

}
