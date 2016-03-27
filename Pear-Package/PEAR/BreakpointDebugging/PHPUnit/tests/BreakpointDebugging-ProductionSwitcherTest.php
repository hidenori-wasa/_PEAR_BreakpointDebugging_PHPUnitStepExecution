<?php

require_once './BreakpointDebugging_ProductionSwitcher.php';

use \BreakpointDebugging_PHPUnit as BU;

class BreakpointDebugging_ProductionSwitcherTest extends \BreakpointDebugging_PHPUnit_FrameworkTestCase
{

    private function _stripCommentForRestoration($results, $linesForTest)
    {
        $isChanged = false;
        BU::callForTest(array (
            'objectOrClassName' => 'BreakpointDebugging_Optimizer',
            'methodName' => 'stripCommentForRestoration',
            'params' => array ('Dummy file path.', &$results, &$isChanged, '', '')
        ));

        parent::assertTrue($linesForTest === $results);
        parent::assertTrue($isChanged === true);
    }

    /**
     * @covers \BreakpointDebugging_ProductionSwitcher<extended>
     */
    function testSetInfoToOptimize()
    {
        \BreakpointDebugging_Optimizer::setInfoToOptimize(__FILE__, 309, 'REPLACE_TO_NATIVE');
    }

    /**
     * @covers \BreakpointDebugging_ProductionSwitcher<extended>
     */
    function testCommentOut_assert()
    {
        $linesForTest = array (
            '<?php' . PHP_EOL,
            '\BreakpointDebugging::assert(true);' . PHP_EOL,
            "\t\\BreakpointDebugging::assert(true);\n",
            "\x20\\BreakpointDebugging::assert(true);\r\n",
            "\x20\t\\BreakpointDebugging::assert(true);" . PHP_EOL,
            "\t\x20\\BreakpointDebugging::assert(true);" . PHP_EOL,
            "\t\x20\\\t\x20BreakpointDebugging\t\x20::\t\x20assert\t\x20(\t\x20true\t\x20)\t\x20;\t\x20" . PHP_EOL,
            '\BreakpointDebugging::assert(true); //' . PHP_EOL,
            '\BreakpointDebugging::assert(true); // Something comment.' . PHP_EOL,
            '\BreakpointDebugging::assert(true); /* */' . PHP_EOL,
            '\BreakpointDebugging::assert(true); /** */' . PHP_EOL,
            '\BreakpointDebugging::assert(true); /*' . PHP_EOL,
            '*/' . PHP_EOL,
            '\BreakpointDebugging::assert(true); /**' . PHP_EOL,
            '*/' . PHP_EOL,
            '\BreakpointDebugging::assert(true); /* Something comment.' . PHP_EOL,
            '*/' . PHP_EOL,
            '\BreakpointDebugging::assert(true); /** Something comment.' . PHP_EOL,
            '*/' . PHP_EOL,
            '\BreakpointDebugging::assert(true); /*' . PHP_EOL,
            'Something comment. */' . PHP_EOL,
            '\BreakpointDebugging::assert(true); /**' . PHP_EOL,
            'Something comment. */' . PHP_EOL,
            '$tmp = <<<EOD' . PHP_EOL,
            '\BreakpointDebugging::assert(true);' . PHP_EOL,
            'EOD;' . PHP_EOL,
            '$tmp = <<<\'EOD\'' . PHP_EOL,
            '\BreakpointDebugging::assert(true);' . PHP_EOL,
            'EOD;' . PHP_EOL,
            '$tmp = <<<"EOD"' . PHP_EOL,
            '\BreakpointDebugging::assert(true);' . PHP_EOL,
            'EOD;' . PHP_EOL,
        );

        $expectedLines = array (
            '<?php' . PHP_EOL,
            '// <BREAKPOINTDEBUGGING_COMMENT> \BreakpointDebugging::assert(true);' . PHP_EOL,
            "\t// <BREAKPOINTDEBUGGING_COMMENT> \\BreakpointDebugging::assert(true);\n",
            "\x20// <BREAKPOINTDEBUGGING_COMMENT> \\BreakpointDebugging::assert(true);\r\n",
            "\x20\t// <BREAKPOINTDEBUGGING_COMMENT> \\BreakpointDebugging::assert(true);" . PHP_EOL,
            "\t\x20// <BREAKPOINTDEBUGGING_COMMENT> \\BreakpointDebugging::assert(true);" . PHP_EOL,
            "\t\x20// <BREAKPOINTDEBUGGING_COMMENT> \\\t\x20BreakpointDebugging\t\x20::\t\x20assert\t\x20(\t\x20true\t\x20)\t\x20;\t\x20" . PHP_EOL,
            '// <BREAKPOINTDEBUGGING_COMMENT> \BreakpointDebugging::assert(true); //' . PHP_EOL,
            '// <BREAKPOINTDEBUGGING_COMMENT> \BreakpointDebugging::assert(true); // Something comment.' . PHP_EOL,
            '\BreakpointDebugging::assert(true); /* */' . PHP_EOL,
            '\BreakpointDebugging::assert(true); /** */' . PHP_EOL,
            '\BreakpointDebugging::assert(true); /*' . PHP_EOL,
            '*/' . PHP_EOL,
            '\BreakpointDebugging::assert(true); /**' . PHP_EOL,
            '*/' . PHP_EOL,
            '\BreakpointDebugging::assert(true); /* Something comment.' . PHP_EOL,
            '*/' . PHP_EOL,
            '\BreakpointDebugging::assert(true); /** Something comment.' . PHP_EOL,
            '*/' . PHP_EOL,
            '\BreakpointDebugging::assert(true); /*' . PHP_EOL,
            'Something comment. */' . PHP_EOL,
            '\BreakpointDebugging::assert(true); /**' . PHP_EOL,
            'Something comment. */' . PHP_EOL,
            '$tmp = <<<EOD' . PHP_EOL,
            '\BreakpointDebugging::assert(true);' . PHP_EOL,
            'EOD;' . PHP_EOL,
            '$tmp = <<<\'EOD\'' . PHP_EOL,
            '\BreakpointDebugging::assert(true);' . PHP_EOL,
            'EOD;' . PHP_EOL,
            '$tmp = <<<"EOD"' . PHP_EOL,
            '\BreakpointDebugging::assert(true);' . PHP_EOL,
            'EOD;' . PHP_EOL,
        );

        $linesToSkip = BU::callForTest(array (
                'objectOrClassName' => 'BreakpointDebugging_ProductionSwitcher',
                'methodName' => '_checkCommentLinesOfPluralLineToSkip',
                'params' => array ($linesForTest)
        ));
        $lineCount = 0;
        foreach ($linesForTest as $lineForTest) {
            $lineCount++;
            if ($linesToSkip[$lineCount]) {
                $results[] = $lineForTest;
                continue;
            }
            $results[] = BU::callForTest(array (
                    'objectOrClassName' => 'BreakpointDebugging_Optimizer',
                    'methodName' => 'commentOut',
                    'params' => array ($lineForTest, BU::getPropertyForTest('BreakpointDebugging_ProductionSwitcher', '$_commentOutRegEx'))
            ));
        }

        parent::assertTrue(count($expectedLines) === count($results));
        for ($count = 0; $count < count($expectedLines); $count++) {
            parent::assertTrue($expectedLines[$count] === $results[$count]);
        }

        $this->_stripCommentForRestoration($results, $linesForTest);
    }

    /**
     * @covers \BreakpointDebugging_ProductionSwitcher<extended>
     */
    function testCommentOut_limitAccess()
    {
        $linesForTest = array (
            '<?php' . PHP_EOL,
            '\BreakpointDebugging::limitAccess(true);' . PHP_EOL,
            "\t\\BreakpointDebugging::limitAccess(true);\n",
            "\x20\\BreakpointDebugging::limitAccess(true);\r\n",
            "\x20\t\\BreakpointDebugging::limitAccess(true);" . PHP_EOL,
            "\t\x20\\BreakpointDebugging::limitAccess(true);" . PHP_EOL,
            "\t\x20\\\t\x20BreakpointDebugging\t\x20::\t\x20limitAccess\t\x20(\t\x20true\t\x20)\t\x20;\t\x20" . PHP_EOL,
            '\BreakpointDebugging::limitAccess(true); //' . PHP_EOL,
            '\BreakpointDebugging::limitAccess(true); // Something comment.' . PHP_EOL,
            '\BreakpointDebugging::limitAccess(true); /* */' . PHP_EOL,
            '\BreakpointDebugging::limitAccess(true); /** */' . PHP_EOL,
            '\BreakpointDebugging::limitAccess(true); /*' . PHP_EOL,
            '*/' . PHP_EOL,
            '\BreakpointDebugging::limitAccess(true); /**' . PHP_EOL,
            '*/' . PHP_EOL,
            '\BreakpointDebugging::limitAccess(true); /* Something comment.' . PHP_EOL,
            '*/' . PHP_EOL,
            '\BreakpointDebugging::limitAccess(true); /** Something comment.' . PHP_EOL,
            '*/' . PHP_EOL,
            '\BreakpointDebugging::limitAccess(true); /*' . PHP_EOL,
            'Something comment. */' . PHP_EOL,
            '\BreakpointDebugging::limitAccess(true); /**' . PHP_EOL,
            'Something comment. */' . PHP_EOL,
            '$tmp = <<<EOD' . PHP_EOL,
            '\BreakpointDebugging::limitAccess(true);' . PHP_EOL,
            'EOD;' . PHP_EOL,
            '$tmp = <<<\'EOD\'' . PHP_EOL,
            '\BreakpointDebugging::limitAccess(true);' . PHP_EOL,
            'EOD;' . PHP_EOL,
            '$tmp = <<<"EOD"' . PHP_EOL,
            '\BreakpointDebugging::limitAccess(true);' . PHP_EOL,
            'EOD;' . PHP_EOL,
        );

        $expectedLines = array (
            '<?php' . PHP_EOL,
            '// <BREAKPOINTDEBUGGING_COMMENT> \BreakpointDebugging::limitAccess(true);' . PHP_EOL,
            "\t// <BREAKPOINTDEBUGGING_COMMENT> \\BreakpointDebugging::limitAccess(true);\n",
            "\x20// <BREAKPOINTDEBUGGING_COMMENT> \\BreakpointDebugging::limitAccess(true);\r\n",
            "\x20\t// <BREAKPOINTDEBUGGING_COMMENT> \\BreakpointDebugging::limitAccess(true);" . PHP_EOL,
            "\t\x20// <BREAKPOINTDEBUGGING_COMMENT> \\BreakpointDebugging::limitAccess(true);" . PHP_EOL,
            "\t\x20// <BREAKPOINTDEBUGGING_COMMENT> \\\t\x20BreakpointDebugging\t\x20::\t\x20limitAccess\t\x20(\t\x20true\t\x20)\t\x20;\t\x20" . PHP_EOL,
            '// <BREAKPOINTDEBUGGING_COMMENT> \BreakpointDebugging::limitAccess(true); //' . PHP_EOL,
            '// <BREAKPOINTDEBUGGING_COMMENT> \BreakpointDebugging::limitAccess(true); // Something comment.' . PHP_EOL,
            '\BreakpointDebugging::limitAccess(true); /* */' . PHP_EOL,
            '\BreakpointDebugging::limitAccess(true); /** */' . PHP_EOL,
            '\BreakpointDebugging::limitAccess(true); /*' . PHP_EOL,
            '*/' . PHP_EOL,
            '\BreakpointDebugging::limitAccess(true); /**' . PHP_EOL,
            '*/' . PHP_EOL,
            '\BreakpointDebugging::limitAccess(true); /* Something comment.' . PHP_EOL,
            '*/' . PHP_EOL,
            '\BreakpointDebugging::limitAccess(true); /** Something comment.' . PHP_EOL,
            '*/' . PHP_EOL,
            '\BreakpointDebugging::limitAccess(true); /*' . PHP_EOL,
            'Something comment. */' . PHP_EOL,
            '\BreakpointDebugging::limitAccess(true); /**' . PHP_EOL,
            'Something comment. */' . PHP_EOL,
            '$tmp = <<<EOD' . PHP_EOL,
            '\BreakpointDebugging::limitAccess(true);' . PHP_EOL,
            'EOD;' . PHP_EOL,
            '$tmp = <<<\'EOD\'' . PHP_EOL,
            '\BreakpointDebugging::limitAccess(true);' . PHP_EOL,
            'EOD;' . PHP_EOL,
            '$tmp = <<<"EOD"' . PHP_EOL,
            '\BreakpointDebugging::limitAccess(true);' . PHP_EOL,
            'EOD;' . PHP_EOL,
        );

        $linesToSkip = BU::callForTest(array (
                'objectOrClassName' => 'BreakpointDebugging_ProductionSwitcher',
                'methodName' => '_checkCommentLinesOfPluralLineToSkip',
                'params' => array ($linesForTest)
        ));
        $lineCount = 0;
        foreach ($linesForTest as $lineForTest) {
            $lineCount++;
            if ($linesToSkip[$lineCount]) {
                $results[] = $lineForTest;
                continue;
            }
            $results[] = BU::callForTest(array (
                    'objectOrClassName' => 'BreakpointDebugging_Optimizer',
                    'methodName' => 'commentOut',
                    'params' => array ($lineForTest, BU::getPropertyForTest('BreakpointDebugging_ProductionSwitcher', '$_commentOutRegEx'))
            ));
        }

        parent::assertTrue(count($expectedLines) === count($results));
        for ($count = 0; $count < count($expectedLines); $count++) {
            parent::assertTrue($expectedLines[$count] === $results[$count]);
        }

        $this->_stripCommentForRestoration($results, $linesForTest);
    }

    /**
     * @covers \BreakpointDebugging_ProductionSwitcher<extended>
     */
    function test_changeModeConstToLiteral_A()
    {
        $linesForTest = array (
            '<?php' . PHP_EOL,
            "            if (\BreakpointDebugging::isDebug()) { // If debug." . PHP_EOL,
            'if(\BreakpointDebugging::isDebug()){' . PHP_EOL,
            "\tif(\\BreakpointDebugging::isDebug()){\n",
            "\x20if(\\BreakpointDebugging::isDebug()){\r\n",
            "\x20\tif(\\BreakpointDebugging::isDebug()){" . PHP_EOL,
            "\t\x20if(\\BreakpointDebugging::isDebug()){" . PHP_EOL,
            "\t\x20if\t\x20(\t\x20!\t\x20\\\t\x20BreakpointDebugging\t\x20::\t\x20isDebug\t\x20(\t\x20)\t\x20)\t\x20{\t\x20" . PHP_EOL,
            'if(\BreakpointDebugging::isDebug()){ echo("abc");' . PHP_EOL,
            'if(\BreakpointDebugging::isDebug()){ //' . PHP_EOL,
            'if(\BreakpointDebugging::isDebug()){ // Something comment.' . PHP_EOL,
            'if(\BreakpointDebugging::isDebug()){ /* */' . PHP_EOL,
            'if(\BreakpointDebugging::isDebug()){ /** */' . PHP_EOL,
            'if(\BreakpointDebugging::isDebug()){ /*' . PHP_EOL,
            '*/' . PHP_EOL,
            'if(\BreakpointDebugging::isDebug()){ /**' . PHP_EOL,
            '*/' . PHP_EOL,
            'if(\BreakpointDebugging::isDebug()){ /* Something comment.' . PHP_EOL,
            '*/' . PHP_EOL,
            'if(\BreakpointDebugging::isDebug()){ /** Something comment.' . PHP_EOL,
            '*/' . PHP_EOL,
            'if(\BreakpointDebugging::isDebug()){ /*' . PHP_EOL,
            'Something comment. */' . PHP_EOL,
            'if(\BreakpointDebugging::isDebug()){ /**' . PHP_EOL,
            'Something comment. */' . PHP_EOL,
            '$tmp = <<<EOD' . PHP_EOL,
            'if(\BreakpointDebugging::isDebug()){' . PHP_EOL,
            'EOD;' . PHP_EOL,
            '$tmp = <<<\'EOD\'' . PHP_EOL,
            'if(\BreakpointDebugging::isDebug()){' . PHP_EOL,
            'EOD;' . PHP_EOL,
            '$tmp = <<<"EOD"' . PHP_EOL,
            'if(\BreakpointDebugging::isDebug()){' . PHP_EOL,
            'EOD;' . PHP_EOL,
        );

        $expectedLines = array (
            '<?php' . PHP_EOL,
            "            /* <BREAKPOINTDEBUGGING_COMMENT> */ if ( false ) { // If debug. // <BREAKPOINTDEBUGGING_COMMENT> if (\BreakpointDebugging::isDebug()) { // If debug." . PHP_EOL,
            '/* <BREAKPOINTDEBUGGING_COMMENT> */ if( false ){ // <BREAKPOINTDEBUGGING_COMMENT> if(\BreakpointDebugging::isDebug()){' . PHP_EOL,
            "\t/* <BREAKPOINTDEBUGGING_COMMENT> */ if( false ){ // <BREAKPOINTDEBUGGING_COMMENT> if(\\BreakpointDebugging::isDebug()){\n",
            "\x20/* <BREAKPOINTDEBUGGING_COMMENT> */ if( false ){ // <BREAKPOINTDEBUGGING_COMMENT> if(\\BreakpointDebugging::isDebug()){\r\n",
            "\x20\t/* <BREAKPOINTDEBUGGING_COMMENT> */ if( false ){ // <BREAKPOINTDEBUGGING_COMMENT> if(\\BreakpointDebugging::isDebug()){" . PHP_EOL,
            "\t\x20/* <BREAKPOINTDEBUGGING_COMMENT> */ if( false ){ // <BREAKPOINTDEBUGGING_COMMENT> if(\\BreakpointDebugging::isDebug()){" . PHP_EOL,
            "\t\x20/* <BREAKPOINTDEBUGGING_COMMENT> */ if\t\x20(\t\x20!\t\x20 false \t\x20)\t\x20{\t\x20 // <BREAKPOINTDEBUGGING_COMMENT> if\t\x20(\t\x20!\t\x20\\\t\x20BreakpointDebugging\t\x20::\t\x20isDebug\t\x20(\t\x20)\t\x20)\t\x20{\t\x20" . PHP_EOL,
            '/* <BREAKPOINTDEBUGGING_COMMENT> */ if( false ){ echo("abc"); // <BREAKPOINTDEBUGGING_COMMENT> if(\BreakpointDebugging::isDebug()){ echo("abc");' . PHP_EOL,
            '/* <BREAKPOINTDEBUGGING_COMMENT> */ if( false ){ // // <BREAKPOINTDEBUGGING_COMMENT> if(\BreakpointDebugging::isDebug()){ //' . PHP_EOL,
            '/* <BREAKPOINTDEBUGGING_COMMENT> */ if( false ){ // Something comment. // <BREAKPOINTDEBUGGING_COMMENT> if(\BreakpointDebugging::isDebug()){ // Something comment.' . PHP_EOL,
            'if(\BreakpointDebugging::isDebug()){ /* */' . PHP_EOL,
            'if(\BreakpointDebugging::isDebug()){ /** */' . PHP_EOL,
            'if(\BreakpointDebugging::isDebug()){ /*' . PHP_EOL,
            '*/' . PHP_EOL,
            'if(\BreakpointDebugging::isDebug()){ /**' . PHP_EOL,
            '*/' . PHP_EOL,
            'if(\BreakpointDebugging::isDebug()){ /* Something comment.' . PHP_EOL,
            '*/' . PHP_EOL,
            'if(\BreakpointDebugging::isDebug()){ /** Something comment.' . PHP_EOL,
            '*/' . PHP_EOL,
            'if(\BreakpointDebugging::isDebug()){ /*' . PHP_EOL,
            'Something comment. */' . PHP_EOL,
            'if(\BreakpointDebugging::isDebug()){ /**' . PHP_EOL,
            'Something comment. */' . PHP_EOL,
            '$tmp = <<<EOD' . PHP_EOL,
            'if(\BreakpointDebugging::isDebug()){' . PHP_EOL,
            'EOD;' . PHP_EOL,
            '$tmp = <<<\'EOD\'' . PHP_EOL,
            'if(\BreakpointDebugging::isDebug()){' . PHP_EOL,
            'EOD;' . PHP_EOL,
            '$tmp = <<<"EOD"' . PHP_EOL,
            'if(\BreakpointDebugging::isDebug()){' . PHP_EOL,
            'EOD;' . PHP_EOL,
        );

        $linesToSkip = BU::callForTest(array (
                'objectOrClassName' => 'BreakpointDebugging_ProductionSwitcher',
                'methodName' => '_checkCommentLinesOfPluralLineToSkip',
                'params' => array ($linesForTest)
        ));
        $lineCount = 0;
        foreach ($linesForTest as $lineForTest) {
            $lineCount++;
            if ($linesToSkip[$lineCount]) {
                $results[] = $lineForTest;
                continue;
            }
            $results[] = BU::callForTest(array (
                    'objectOrClassName' => 'BreakpointDebugging_ProductionSwitcher',
                    'methodName' => '_changeModeConstToLiteral',
                    'params' => array ($lineForTest, BU::getPropertyForTest('BreakpointDebugging_ProductionSwitcher', '$_isDebugRegEx'), 'false')
            ));
        }

        for ($count = 0; $count < count($expectedLines); $count++) {
            parent::assertTrue($expectedLines[$count] === $results[$count]);
        }

        $this->_stripCommentForRestoration($results, $linesForTest);
    }

    /**
     * @covers \BreakpointDebugging_ProductionSwitcher<extended>
     */
    function test_changeModeConstToLiteral_B()
    {
        $linesForTest = array (
            '<?php' . PHP_EOL,
            'if(BREAKPOINTDEBUGGING_IS_PRODUCTION){' . PHP_EOL,
            "\tif(BREAKPOINTDEBUGGING_IS_PRODUCTION){\n",
            "\x20if(BREAKPOINTDEBUGGING_IS_PRODUCTION){\r\n",
            "\x20\tif(BREAKPOINTDEBUGGING_IS_PRODUCTION){" . PHP_EOL,
            "\t\x20if(BREAKPOINTDEBUGGING_IS_PRODUCTION){" . PHP_EOL,
            "\t\x20if\t\x20(\t\x20!\t\x20BREAKPOINTDEBUGGING_IS_PRODUCTION\t\x20)\t\x20{\t\x20" . PHP_EOL,
            'if(BREAKPOINTDEBUGGING_IS_PRODUCTION){ echo("abc");' . PHP_EOL,
            'if(BREAKPOINTDEBUGGING_IS_PRODUCTION){ //' . PHP_EOL,
            'if(BREAKPOINTDEBUGGING_IS_PRODUCTION){ // Something comment.' . PHP_EOL,
            'if(BREAKPOINTDEBUGGING_IS_PRODUCTION){ /* */' . PHP_EOL,
            'if(BREAKPOINTDEBUGGING_IS_PRODUCTION){ /** */' . PHP_EOL,
            'if(BREAKPOINTDEBUGGING_IS_PRODUCTION){ /*' . PHP_EOL,
            '*/' . PHP_EOL,
            'if(BREAKPOINTDEBUGGING_IS_PRODUCTION){ /**' . PHP_EOL,
            '*/' . PHP_EOL,
            'if(BREAKPOINTDEBUGGING_IS_PRODUCTION){ /* Something comment.' . PHP_EOL,
            '*/' . PHP_EOL,
            'if(BREAKPOINTDEBUGGING_IS_PRODUCTION){ /** Something comment.' . PHP_EOL,
            '*/' . PHP_EOL,
            'if(BREAKPOINTDEBUGGING_IS_PRODUCTION){ /*' . PHP_EOL,
            'Something comment. */' . PHP_EOL,
            'if(BREAKPOINTDEBUGGING_IS_PRODUCTION){ /**' . PHP_EOL,
            'Something comment. */' . PHP_EOL,
            '$tmp = <<<EOD' . PHP_EOL,
            'if(BREAKPOINTDEBUGGING_IS_PRODUCTION){' . PHP_EOL,
            'EOD;' . PHP_EOL,
            '$tmp = <<<\'EOD\'' . PHP_EOL,
            'if(BREAKPOINTDEBUGGING_IS_PRODUCTION){' . PHP_EOL,
            'EOD;' . PHP_EOL,
            '$tmp = <<<"EOD"' . PHP_EOL,
            'if(BREAKPOINTDEBUGGING_IS_PRODUCTION){' . PHP_EOL,
            'EOD;' . PHP_EOL,
        );
        $expectedLines = array (
            '<?php' . PHP_EOL,
            '/* <BREAKPOINTDEBUGGING_COMMENT> */ if( true ){ // <BREAKPOINTDEBUGGING_COMMENT> if(BREAKPOINTDEBUGGING_IS_PRODUCTION){' . PHP_EOL,
            "\t/* <BREAKPOINTDEBUGGING_COMMENT> */ if( true ){ // <BREAKPOINTDEBUGGING_COMMENT> if(BREAKPOINTDEBUGGING_IS_PRODUCTION){\n",
            "\x20/* <BREAKPOINTDEBUGGING_COMMENT> */ if( true ){ // <BREAKPOINTDEBUGGING_COMMENT> if(BREAKPOINTDEBUGGING_IS_PRODUCTION){\r\n",
            "\x20\t/* <BREAKPOINTDEBUGGING_COMMENT> */ if( true ){ // <BREAKPOINTDEBUGGING_COMMENT> if(BREAKPOINTDEBUGGING_IS_PRODUCTION){" . PHP_EOL,
            "\t\x20/* <BREAKPOINTDEBUGGING_COMMENT> */ if( true ){ // <BREAKPOINTDEBUGGING_COMMENT> if(BREAKPOINTDEBUGGING_IS_PRODUCTION){" . PHP_EOL,
            "\t\x20/* <BREAKPOINTDEBUGGING_COMMENT> */ if\t\x20(\t\x20!\t\x20 true \t\x20)\t\x20{\t\x20 // <BREAKPOINTDEBUGGING_COMMENT> if\t\x20(\t\x20!\t\x20BREAKPOINTDEBUGGING_IS_PRODUCTION\t\x20)\t\x20{\t\x20" . PHP_EOL,
            '/* <BREAKPOINTDEBUGGING_COMMENT> */ if( true ){ echo("abc"); // <BREAKPOINTDEBUGGING_COMMENT> if(BREAKPOINTDEBUGGING_IS_PRODUCTION){ echo("abc");' . PHP_EOL,
            '/* <BREAKPOINTDEBUGGING_COMMENT> */ if( true ){ // // <BREAKPOINTDEBUGGING_COMMENT> if(BREAKPOINTDEBUGGING_IS_PRODUCTION){ //' . PHP_EOL,
            '/* <BREAKPOINTDEBUGGING_COMMENT> */ if( true ){ // Something comment. // <BREAKPOINTDEBUGGING_COMMENT> if(BREAKPOINTDEBUGGING_IS_PRODUCTION){ // Something comment.' . PHP_EOL,
            'if(BREAKPOINTDEBUGGING_IS_PRODUCTION){ /* */' . PHP_EOL,
            'if(BREAKPOINTDEBUGGING_IS_PRODUCTION){ /** */' . PHP_EOL,
            'if(BREAKPOINTDEBUGGING_IS_PRODUCTION){ /*' . PHP_EOL,
            '*/' . PHP_EOL,
            'if(BREAKPOINTDEBUGGING_IS_PRODUCTION){ /**' . PHP_EOL,
            '*/' . PHP_EOL,
            'if(BREAKPOINTDEBUGGING_IS_PRODUCTION){ /* Something comment.' . PHP_EOL,
            '*/' . PHP_EOL,
            'if(BREAKPOINTDEBUGGING_IS_PRODUCTION){ /** Something comment.' . PHP_EOL,
            '*/' . PHP_EOL,
            'if(BREAKPOINTDEBUGGING_IS_PRODUCTION){ /*' . PHP_EOL,
            'Something comment. */' . PHP_EOL,
            'if(BREAKPOINTDEBUGGING_IS_PRODUCTION){ /**' . PHP_EOL,
            'Something comment. */' . PHP_EOL,
            '$tmp = <<<EOD' . PHP_EOL,
            'if(BREAKPOINTDEBUGGING_IS_PRODUCTION){' . PHP_EOL,
            'EOD;' . PHP_EOL,
            '$tmp = <<<\'EOD\'' . PHP_EOL,
            'if(BREAKPOINTDEBUGGING_IS_PRODUCTION){' . PHP_EOL,
            'EOD;' . PHP_EOL,
            '$tmp = <<<"EOD"' . PHP_EOL,
            'if(BREAKPOINTDEBUGGING_IS_PRODUCTION){' . PHP_EOL,
            'EOD;' . PHP_EOL,
        );

        $linesToSkip = BU::callForTest(array (
                'objectOrClassName' => 'BreakpointDebugging_ProductionSwitcher',
                'methodName' => '_checkCommentLinesOfPluralLineToSkip',
                'params' => array ($linesForTest)
        ));
        $lineCount = 0;
        foreach ($linesForTest as $lineForTest) {
            $lineCount++;
            if ($linesToSkip[$lineCount]) {
                $results[] = $lineForTest;
                continue;
            }
            $results[] = BU::callForTest(array (
                    'objectOrClassName' => 'BreakpointDebugging_ProductionSwitcher',
                    'methodName' => '_changeModeConstToLiteral',
                    'params' => array ($lineForTest, BU::getPropertyForTest('BreakpointDebugging_ProductionSwitcher', '$_breakpointdebuggingIsProductionRegEx'), 'true')
            ));
        }

        for ($count = 0; $count < count($expectedLines); $count++) {
            parent::assertTrue($expectedLines[$count] === $results[$count]);
        }

        $this->_stripCommentForRestoration($results, $linesForTest);
    }

    public function stripCommentForRestoration_provider()
    {
        return array (
            array ('/* <BREAKPOINTDEBUGGING_COMMENT> */ if( false ){'),
            array ("\t/* <BREAKPOINTDEBUGGING_COMMENT> */ if( false ){\n"),
            array ("\x20/* <BREAKPOINTDEBUGGING_COMMENT> */ if( false ){\r\n"),
            array ("\x20\t/* <BREAKPOINTDEBUGGING_COMMENT> */ if( false ){"),
            array ("\t\x20/* <BREAKPOINTDEBUGGING_COMMENT> */ if( false ){"),
            array ("\t\x20/* <BREAKPOINTDEBUGGING_COMMENT> */ if\t\x20(\t\x20!\t\x20 false \t\x20)\t\x20{\t\x20"),
            array ('/* <BREAKPOINTDEBUGGING_COMMENT> */ if( false ){ //'),
            array ('/* <BREAKPOINTDEBUGGING_COMMENT> */ if( false ){ // Something comment.'),
        );
    }

    /**
     * @covers \BreakpointDebugging_ProductionSwitcher<extended>
     *
     * @dataProvider             stripCommentForRestoration_provider
     * @expectedException        \BreakpointDebugging_ErrorException
     * @expectedExceptionMessage CLASS=BreakpointDebugging_Window FUNCTION=throwErrorException.
     */
    function testStripCommentForRestoration($lineForTest)
    {
        BU::ignoreBreakpoint();
        $this->_stripCommentForRestoration(array ($lineForTest), 'DUMMY');
    }

}
