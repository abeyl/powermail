<?php
namespace In2code\Powermail\Tests\Domain\Service;

use TYPO3\CMS\Core\Configuration\ConfigurationManager;
use TYPO3\CMS\Core\Mail\MailMessage;
use TYPO3\CMS\Core\Tests\UnitTestCase;
use TYPO3\CMS\Core\TimeTracker\TimeTracker;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2014 Alex Kellner <alexander.kellner@in2code.de>, in2code.de
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * SendMail Tests
 *
 * @package powermail
 * @license http://www.gnu.org/licenses/lgpl.html
 *          GNU Lesser General Public License, version 3 or later
 */
class SendMailServiceTest extends UnitTestCase
{

    /**
     * @var \In2code\Powermail\Domain\Service\SendMailService
     */
    protected $generalValidatorMock;

    /**
     * @return void
     */
    public function setUp()
    {
        $this->generalValidatorMock = $this->getAccessibleMock(
            '\In2code\Powermail\Domain\Service\SendMailService',
            ['addSender']
        );
    }

    /**
     * @return void
     */
    public function tearDown()
    {
        unset($this->generalValidatorMock);
    }

    /**
     * Data Provider for addCcReturnMailMessage()
     *
     * @return array
     */
    public function addCcReturnMailMessageDataProvider()
    {
        return [
            '1 cc' => [
                [
                    'cc' => 'TEXT',
                    'cc.' => [
                        'value' => 'rec@domain.org'
                    ],
                ],
                [
                    'rec@domain.org' => ''
                ]
            ],
            '3 cc' => [
                [
                    'cc' => 'TEXT',
                    'cc.' => [
                        'value' => 'rec1@domain.org,rec2@domain.org,rec3@domain.org,'
                    ],
                ],
                [
                    'rec1@domain.org' => '',
                    'rec2@domain.org' => '',
                    'rec3@domain.org' => ''
                ]
            ],
            '0 cc' => [
                [
                    'cc' => 'TEXT'
                ],
                null
            ],
        ];
    }

    /**
     * addCc Test
     *
     * @param array $overwriteConfig
     * @param array|null $expectedResult
     * @dataProvider addCcReturnMailMessageDataProvider
     * @return void
     * @test
     */
    public function addCcReturnMailMessage(array $overwriteConfig, $expectedResult)
    {
        $this->initializeTsfe();
        $message = new MailMessage();
        $this->generalValidatorMock->_set('overwriteConfig', $overwriteConfig);
        $this->generalValidatorMock->_set('contentObject', new ContentObjectRenderer());
        $message = $this->generalValidatorMock->_call('addCc', $message);
        $this->assertEquals($expectedResult, $message->getCc());
    }

    /**
     * Data Provider for addBccReturnMailMessage()
     *
     * @return array
     */
    public function addBccReturnMailMessageDataProvider()
    {
        return [
            '1 bcc' => [
                [
                    'bcc' => 'TEXT',
                    'bcc.' => [
                        'value' => 'rec@domain.org'
                    ],
                ],
                [
                    'rec@domain.org' => ''
                ]
            ],
            '3 bcc' => [
                [
                    'bcc' => 'TEXT',
                    'bcc.' => [
                        'value' => 'rec1@domain.org,rec2@domain.org,rec3@domain.org,'
                    ],
                ],
                [
                    'rec1@domain.org' => '',
                    'rec2@domain.org' => '',
                    'rec3@domain.org' => ''
                ]
            ],
            '0 bcc' => [
                [
                    'bcc' => 'TEXT'
                ],
                null
            ],
        ];
    }

    /**
     * addBcc Test
     *
     * @param array $overwriteConfig
     * @param array|null $expectedResult
     * @dataProvider addBccReturnMailMessageDataProvider
     * @return void
     * @test
     */
    public function addBccReturnMailMessage(array $overwriteConfig, $expectedResult)
    {
        $this->initializeTsfe();
        $message = new MailMessage();
        $this->generalValidatorMock->_set('overwriteConfig', $overwriteConfig);
        $this->generalValidatorMock->_set('contentObject', new ContentObjectRenderer());
        $message = $this->generalValidatorMock->_call('addBcc', $message);
        $this->assertEquals($expectedResult, $message->getBcc());
    }

    /**
     * Data Provider for addReturnPathReturnMailMessage()
     *
     * @return array
     */
    public function addReturnPathReturnMailMessageDataProvider()
    {
        return [
            'returnpath set' => [
                [
                    'returnPath' => 'TEXT',
                    'returnPath.' => [
                        'value' => 'rec@domain.org'
                    ],
                ],
                'rec@domain.org'
            ],
            'returnpath empty' => [
                [
                    'returnPath' => 'TEXT'
                ],
                null
            ],
        ];
    }

    /**
     * addReturnPath Test
     *
     * @param array $overwriteConfig
     * @param string|null $expectedResult
     * @dataProvider addReturnPathReturnMailMessageDataProvider
     * @return void
     * @test
     */
    public function addReturnPathReturnMailMessage(array $overwriteConfig, $expectedResult)
    {
        $this->initializeTsfe();
        $message = new MailMessage();
        $this->generalValidatorMock->_set('overwriteConfig', $overwriteConfig);
        $this->generalValidatorMock->_set('contentObject', new ContentObjectRenderer());
        $message = $this->generalValidatorMock->_call('addReturnPath', $message);
        $this->assertEquals($expectedResult, $message->getReturnPath());
    }

    /**
     * Data Provider for addReplyAddressesReturnMailMessage()
     *
     * @return array
     */
    public function addReplyAddressesReturnMailMessageDataProvider()
    {
        return [
            'reply set' => [
                [
                    'replyToEmail' => 'TEXT',
                    'replyToEmail.' => [
                        'value' => 'rec@domain.org'
                    ],
                    'replyToName' => 'TEXT',
                    'replyToName.' => [
                        'value' => 'receiver'
                    ],
                ],
                [
                    'rec@domain.org' => 'receiver'
                ]
            ],
            'reply empty' => [
                [
                    'replyToEmail' => 'TEXT',
                    'replyToName' => 'TEXT'
                ],
                null
            ],
        ];
    }

    /**
     * addReplyAddresses Test
     *
     * @param array $overwriteConfig
     * @param array|null $expectedResult
     * @dataProvider addReplyAddressesReturnMailMessageDataProvider
     * @return void
     * @test
     */
    public function addReplyAddressesReturnMailMessage(array $overwriteConfig, $expectedResult)
    {
        $this->initializeTsfe();
        $message = new MailMessage();
        $this->generalValidatorMock->_set('overwriteConfig', $overwriteConfig);
        $this->generalValidatorMock->_set('contentObject', new ContentObjectRenderer());
        $message = $this->generalValidatorMock->_call('addReplyAddresses', $message);
        $this->assertEquals($expectedResult, $message->getReplyTo());
    }

    /**
     * Data Provider for addPriorityReturnMailMessage()
     *
     * @return array
     */
    public function addPriorityReturnMailMessageDataProvider()
    {
        return [
            'priority set' => [
                [
                    'priority' => '2'
                ],
                2
            ],
            'reply empty' => [
                [
                    'priority' => null
                ],
                3
            ],
        ];
    }

    /**
     * addPriority Test
     *
     * @param array $overwriteConfig
     * @param array|null $expectedResult
     * @dataProvider addPriorityReturnMailMessageDataProvider
     * @return void
     * @test
     */
    public function addPriorityReturnMailMessage(array $overwriteConfig, $expectedResult)
    {
        $this->initializeTsfe();
        $message = new MailMessage();
        $this->generalValidatorMock->_set('type', 'receiver');
        $this->generalValidatorMock->_set(
            'settings',
            [
                'receiver' => [
                    'overwrite' => $overwriteConfig
                ]
            ]
        );
        $this->generalValidatorMock->_set('contentObject', new ContentObjectRenderer());
        $message = $this->generalValidatorMock->_call('addPriority', $message);
        $this->assertEquals($expectedResult, $message->getPriority());
    }

    /**
     * Data Provider for addSenderHeaderReturnMailMessage()
     *
     * @return array
     */
    public function addSenderHeaderReturnMailMessageDataProvider()
    {
        return [
            'null 1' => [
                [],
                null
            ],
            'null 2' => [
                [
                    'email' => 'TEXT',
                    'email.' => [
                        'value' => 'test@test'
                    ]
                ],
                null
            ],
            'email, no name' => [
                [
                    'email' => 'TEXT',
                    'email.' => [
                        'value' => 'test@test.org'
                    ]
                ],
                [
                    'test@test.org' => ''
                ]
            ],
            'email and name' => [
                [
                    'email' => 'TEXT',
                    'email.' => [
                        'value' => 'test@test.org'
                    ],
                    'name' => 'TEXT',
                    'name.' => [
                        'value' => 'name'
                    ]
                ],
                [
                    'test@test.org' => 'name'
                ]
            ]
        ];
    }

    /**
     * addSenderHeader Test
     *
     * @param array $config
     * @param array|null $expectedResult
     * @dataProvider addSenderHeaderReturnMailMessageDataProvider
     * @return void
     * @test
     */
    public function addSenderHeaderReturnMailMessage($config, $expectedResult)
    {
        $this->initializeTsfe();
        $message = new MailMessage();
        $this->generalValidatorMock->_set('type', 'receiver');
        $this->generalValidatorMock->_set(
            'configuration',
            [
                'receiver.' => [
                    'senderHeader.' => $config
                ]
            ]
        );
        $this->generalValidatorMock->_set('contentObject', new ContentObjectRenderer());
        $message = $this->generalValidatorMock->_call('addSenderHeader', $message);
        $this->assertEquals($expectedResult, $message->getSender());
    }

    /**
     * Data Provider for makePlainReturnString()
     *
     * @return array
     */
    public function makePlainReturnStringDataProvider()
    {
        return [
            [
                'a<br>b',
                "a\nb"
            ],
            [
                '<p>test</p><p>test</p>',
                "test\ntest"
            ],
            [
                "<table>\n\t\n<tr><th>a</th><th>b</th></tr><td>\nc</td><td>d</td></table>",
                "a b \nc d"
            ],
            [
                '<h1>t</h1><p>p</p><br>x',
                "t\np\n\nx"
            ],
            [
                'a<ul><li>b</li><li>c</li></ul>d',
                "a\nb\nc\nd"
            ],
            [
                '<head><title>x</title></head>a<ul><li>b</li><li>c</li></ul>d',
                "a\nb\nc\nd"
            ],
            [
                'Please click <a href="http://www.google.com">this</a> link',
                'Please click this [http://www.google.com] link'
            ],
            [
                'Please click <a class="a b href" href="http://www.google.com" id="text" target="_blank">this</a> link',
                'Please click this [http://www.google.com] link'
            ],
            [
                'Please click <a class="a b href" href="http://www.google.com" id="text" target="_blank">this</a> ' .
                    'or <a rel="lightbox" href="https://www.in2code.de" data-foo="bar">this</a> ' .
                    'or <a href="http://www.test.de">this</a> link',
                'Please click this [http://www.google.com] or this [https://www.in2code.de] or ' .
                    'this [http://www.test.de] link'
            ],
        ];
    }

    /**
     * cleanFileNameReturnBool Test
     *
     * @param string $content
     * @param string $expectedResult
     * @dataProvider makePlainReturnStringDataProvider
     * @return void
     * @test
     */
    public function makePlainReturnString($content, $expectedResult)
    {
        $result = $this->generalValidatorMock->_call('makePlain', $content);
        $this->assertSame($expectedResult, $result);
    }

    /**
     * Initialize TSFE object
     *
     * @return void
     */
    protected function initializeTsfe()
    {
        $configurationManager = new ConfigurationManager();
        $GLOBALS['TYPO3_CONF_VARS'] = $configurationManager->getDefaultConfiguration();
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['trustedHostsPattern'] = '.*';
        $GLOBALS['TYPO3_CONF_VARS']['FE']['ContentObjects'] = [
            'TEXT' => 'TYPO3\CMS\Frontend\ContentObject\TextContentObject'
        ];
        $GLOBALS['TT'] = new TimeTracker();
        $GLOBALS['TSFE'] = new TypoScriptFrontendController($GLOBALS['TYPO3_CONF_VARS'], 1, 0, true);
    }
}
