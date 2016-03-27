<?php

require_once './BreakpointDebugging_IniSetOptimizer.php';

use \BreakpointDebugging_PHPUnit as BU;

class BreakpointDebugging_IniSetOptimizerTest extends \BreakpointDebugging_PHPUnit_FrameworkTestCase
{

    private function _stripCommentForRestoration($results, $linesForTest)
    {
        $isChanged = false;
        BU::callForTest(array (
            'objectOrClassName' => 'BreakpointDebugging_Optimizer',
            'methodName' => 'stripCommentForRestoration',
            'params' => array ('Dummy file path.', &$results, &$isChanged, '[[:blank:]]* ini_set [[:blank:]]* \(', '\\\\ [[:blank:]]* BreakpointDebugging [[:blank:]]* :: [[:blank:]]* ( iniSet | iniCheck ) [[:blank:]]* \(')
        ));

        parent::assertTrue($linesForTest === $results);
        parent::assertTrue($isChanged === true);
    }

    /**
     * @covers \BreakpointDebugging_IniSetOptimizer<extended>
     */
    function testSetInfoToOptimize()
    {
        \BreakpointDebugging_Optimizer::setInfoToOptimize(__FILE__, 309, 'REPLACE_TO_NATIVE');
    }

    /**
     * @covers \BreakpointDebugging_IniSetOptimizer<extended>
     */
    function testCommentOut_A()
    {
        $linesForTest = array (
            '\BreakpointDebugging::iniCheck(true);',
            "\t\\BreakpointDebugging::iniCheck(true);\n",
            "\x20\\BreakpointDebugging::iniCheck(true);\r\n",
            "\x20\t\\BreakpointDebugging::iniCheck(true);",
            "\t\x20\\BreakpointDebugging::iniCheck(true);",
            "\t\x20\\\t\x20BreakpointDebugging\t\x20::\t\x20iniCheck\t\x20(\t\x20true\t\x20)\t\x20;\t\x20",
            '\BreakpointDebugging::iniCheck(true); //',
            '\BreakpointDebugging::iniCheck(true); // Something comment.',
            '\BreakpointDebugging::iniCheck(true); /',
            '\BreakpointDebugging::iniCheck(true); / Something comment.',
            '\BreakpointDebugging::iniCheck(true); /*',
            '\BreakpointDebugging::iniCheck(true); /* Something comment.',
        );
        $expectedLines = array (
            '// <BREAKPOINTDEBUGGING_COMMENT> \BreakpointDebugging::iniCheck(true);',
            "\t// <BREAKPOINTDEBUGGING_COMMENT> \\BreakpointDebugging::iniCheck(true);\n",
            "\x20// <BREAKPOINTDEBUGGING_COMMENT> \\BreakpointDebugging::iniCheck(true);\r\n",
            "\x20\t// <BREAKPOINTDEBUGGING_COMMENT> \\BreakpointDebugging::iniCheck(true);",
            "\t\x20// <BREAKPOINTDEBUGGING_COMMENT> \\BreakpointDebugging::iniCheck(true);",
            "\t\x20// <BREAKPOINTDEBUGGING_COMMENT> \\\t\x20BreakpointDebugging\t\x20::\t\x20iniCheck\t\x20(\t\x20true\t\x20)\t\x20;\t\x20",
            '// <BREAKPOINTDEBUGGING_COMMENT> \BreakpointDebugging::iniCheck(true); //',
            '// <BREAKPOINTDEBUGGING_COMMENT> \BreakpointDebugging::iniCheck(true); // Something comment.',
            '\BreakpointDebugging::iniCheck(true); /',
            '\BreakpointDebugging::iniCheck(true); / Something comment.',
            '\BreakpointDebugging::iniCheck(true); /*',
            '\BreakpointDebugging::iniCheck(true); /* Something comment.',
        );

        foreach ($linesForTest as $lineForTest) {
            $results[] = BU::callForTest(array (
                    'objectOrClassName' => 'BreakpointDebugging_Optimizer',
                    'methodName' => 'commentOut',
                    'params' => array ($lineForTest, BU::getPropertyForTest('BreakpointDebugging_IniSetOptimizer', '$_commentOutRegEx'))
            ));
        }

        foreach ($expectedLines as $key => $expectedLine) {
            parent::assertTrue($expectedLine === $results[$key]);
        }

        $this->_stripCommentForRestoration($results, $linesForTest);
    }

    /**
     * @covers \BreakpointDebugging_IniSetOptimizer<extended>
     */
    function testCommentOut_B()
    {
        $linesForTest = array (
            '\BreakpointDebugging::iniSet(true);',
            "\t\\BreakpointDebugging::iniSet(true);\n",
            "\x20\\BreakpointDebugging::iniSet(true);\r\n",
            "\x20\t\\BreakpointDebugging::iniSet(true);",
            "\t\x20\\BreakpointDebugging::iniSet(true);",
            "\t\x20\\\t\x20BreakpointDebugging\t\x20::\t\x20iniSet\t\x20(\t\x20true\t\x20)\t\x20;\t\x20",
            '\BreakpointDebugging::iniSet(true); //',
            '\BreakpointDebugging::iniSet(true); // Something comment.',
            '\BreakpointDebugging::iniSet(true); /',
            '\BreakpointDebugging::iniSet(true); / Something comment.',
            '\BreakpointDebugging::iniSet(true); /*',
            '\BreakpointDebugging::iniSet(true); /* Something comment.',
        );
        $expectedLines = array (
            '// <BREAKPOINTDEBUGGING_COMMENT> \BreakpointDebugging::iniSet(true);',
            "\t// <BREAKPOINTDEBUGGING_COMMENT> \\BreakpointDebugging::iniSet(true);\n",
            "\x20// <BREAKPOINTDEBUGGING_COMMENT> \\BreakpointDebugging::iniSet(true);\r\n",
            "\x20\t// <BREAKPOINTDEBUGGING_COMMENT> \\BreakpointDebugging::iniSet(true);",
            "\t\x20// <BREAKPOINTDEBUGGING_COMMENT> \\BreakpointDebugging::iniSet(true);",
            "\t\x20// <BREAKPOINTDEBUGGING_COMMENT> \\\t\x20BreakpointDebugging\t\x20::\t\x20iniSet\t\x20(\t\x20true\t\x20)\t\x20;\t\x20",
            '// <BREAKPOINTDEBUGGING_COMMENT> \BreakpointDebugging::iniSet(true); //',
            '// <BREAKPOINTDEBUGGING_COMMENT> \BreakpointDebugging::iniSet(true); // Something comment.',
            '\BreakpointDebugging::iniSet(true); /',
            '\BreakpointDebugging::iniSet(true); / Something comment.',
            '\BreakpointDebugging::iniSet(true); /*',
            '\BreakpointDebugging::iniSet(true); /* Something comment.',
        );

        foreach ($linesForTest as $lineForTest) {
            $results[] = BU::callForTest(array (
                    'objectOrClassName' => 'BreakpointDebugging_Optimizer',
                    'methodName' => 'commentOut',
                    'params' => array ($lineForTest, BU::getPropertyForTest('BreakpointDebugging_IniSetOptimizer', '$_commentOutRegEx'))
            ));
        }

        parent::assertTrue($expectedLines === $results);

        $this->_stripCommentForRestoration($results, $linesForTest);
    }

    /**
     * @covers \BreakpointDebugging_IniSetOptimizer<extended>
     */
    function test_replaceIniSetToNative_A()
    {
        $linesForTest = array (
            '\BreakpointDebugging::iniSet(true);',
            "\t\\BreakpointDebugging::iniSet(true);\n",
            "\x20\\BreakpointDebugging::iniSet(true);\r\n",
            "\x20\t\\BreakpointDebugging::iniSet(true);",
            "\t\x20\\BreakpointDebugging::iniSet(true);",
            "\t\x20\\\t\x20BreakpointDebugging\t\x20::\t\x20iniSet\t\x20(\t\x20true\t\x20)\t\x20;\t\x20",
            '\BreakpointDebugging::iniSet(true); //',
            '\BreakpointDebugging::iniSet(true); // Something comment.',
        );
        $expectedLines = array (
            '/* <BREAKPOINTDEBUGGING_COMMENT> */ ini_set (true); // <BREAKPOINTDEBUGGING_COMMENT> \BreakpointDebugging::iniSet(true);',
            "\t/* <BREAKPOINTDEBUGGING_COMMENT> */ ini_set (true); // <BREAKPOINTDEBUGGING_COMMENT> \\BreakpointDebugging::iniSet(true);\n",
            "\x20/* <BREAKPOINTDEBUGGING_COMMENT> */ ini_set (true); // <BREAKPOINTDEBUGGING_COMMENT> \\BreakpointDebugging::iniSet(true);\r\n",
            "\x20\t/* <BREAKPOINTDEBUGGING_COMMENT> */ ini_set (true); // <BREAKPOINTDEBUGGING_COMMENT> \\BreakpointDebugging::iniSet(true);",
            "\t\x20/* <BREAKPOINTDEBUGGING_COMMENT> */ ini_set (true); // <BREAKPOINTDEBUGGING_COMMENT> \\BreakpointDebugging::iniSet(true);",
            "\t\x20/* <BREAKPOINTDEBUGGING_COMMENT> */ ini_set (\t\x20true\t\x20)\t\x20;\t\x20 // <BREAKPOINTDEBUGGING_COMMENT> \\\t\x20BreakpointDebugging\t\x20::\t\x20iniSet\t\x20(\t\x20true\t\x20)\t\x20;\t\x20",
            '/* <BREAKPOINTDEBUGGING_COMMENT> */ ini_set (true); // // <BREAKPOINTDEBUGGING_COMMENT> \BreakpointDebugging::iniSet(true); //',
            '/* <BREAKPOINTDEBUGGING_COMMENT> */ ini_set (true); // Something comment. // <BREAKPOINTDEBUGGING_COMMENT> \BreakpointDebugging::iniSet(true); // Something comment.',
        );

        foreach ($linesForTest as $lineForTest) {
            // Replaces "\BreakpointDebugging::iniSet(..." line to "/* <BREAKPOINTDEBUGGING_COMMENT> */ ini_set(... // <BREAKPOINTDEBUGGING_COMMENT> <native code>".
            $results[] = BU::callForTest(array (
                    'objectOrClassName' => 'BreakpointDebugging_IniSetOptimizer',
                    'methodName' => '_replaceIniSetToNative',
                    'params' => array ($lineForTest)
            ));
        }

        parent::assertTrue($expectedLines === $results);

        $this->_stripCommentForRestoration($results, $linesForTest);
    }

    public function _replaceIniSetToNative_B_provider()
    {
        return array (
            array ('\BreakpointDebugging::iniSet(true); /'),
            array ('\BreakpointDebugging::iniSet(true); / Something comment.'),
            array ('\BreakpointDebugging::iniSet(true); /*'),
            array ('\BreakpointDebugging::iniSet(true); /* Something comment.'),
        );
    }

    /**
     * @covers \BreakpointDebugging_IniSetOptimizer<extended>
     *
     * @dataProvider             _replaceIniSetToNative_B_provider
     * @expectedException        \BreakpointDebugging_ErrorException
     * @expectedExceptionMessage CLASS=BreakpointDebugging_IniSetOptimizer FUNCTION=_replaceIniSetToNative ID=1.
     */
    function test_replaceIniSetToNative_B($lineForTest)
    {
        $expectedLine = $lineForTest;

        $result = BU::callForTest(array (
                'objectOrClassName' => 'BreakpointDebugging_IniSetOptimizer',
                'methodName' => '_replaceIniSetToNative',
                'params' => array ($lineForTest)
        ));
    }

}
