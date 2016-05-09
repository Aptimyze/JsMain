<?php
/**
 * Class Minify_ClosureCompiler
 * @package Minify
 */

/**
 * Compress Javascript using the Closure Compiler
 *
 * You must set $jarFile and $tempDir before calling the minify functions.
 * Also, depending on your shell's environment, you may need to specify
 * the full path to java in $javaExecutable or use putenv() to setup the
 * Java environment.
 *
 * <code>
 * Minify_ClosureCompiler::$jarFile = '/path/to/closure-compiler-20120123.jar';
 * Minify_ClosureCompiler::$tempDir = '/tmp';
 * $code = Minify_ClosureCompiler::minify(
 *   $code,
 *   array('compilation_level' => 'SIMPLE_OPTIMIZATIONS')
 * );
 *
 * --compilation_level WHITESPACE_ONLY, SIMPLE_OPTIMIZATIONS, ADVANCED_OPTIMIZATIONS
 *
 * </code>
 *
 * @todo unit tests, $options docs
 * @todo more options support (or should just passthru them all?)
 *
 * @package Minify
 * @author Stephen Clay <steve@mrclay.org>
 * @author Elan Ruusamäe <glen@delfi.ee>
 */
class Minify_ClosureCompiler {
	
    /**
     * Filepath of the Closure Compiler jar file. This must be set before
     * calling minifyJs().
     *
     * @var string
     */
    public static $jarFile ;

    /**
     * Writable temp directory. This must be set before calling minifyJs().
     *
     * @var string
     */
    public static $tempDir ;

    /**
     * Filepath of "java" executable (may be needed if not in shell's PATH)
     *
     * @var string
     */
    public static $javaExecutable = '';
    //added by pankaj
    /**
     *
     * @param array $options
     *
     * fallbackFunc : default array($this, 'fallback');
     */
    public function __construct(array $options = array())
    {
		$this->_fallbackFunc = isset($options['fallbackMinifier'])
            ? $options['fallbackMinifier']
            : array($this, '_fallback');
			self::$tempDir = JsConstants::$cronDocRoot.'/cache';
       self::$jarFile = JsConstants::$docRoot.'/min/jar/compiler.jar';
       self::$javaExecutable = JsConstants::$java;
    }


	public static function minify($js,$options = array())
	{
		$obj = new self($options);
        return $obj->min($js,$options);
    }
    /**
     * Minify a Javascript string
     *
     * @param string $js
     *
     * @param array $options (verbose is ignored)
     *
     * @see https://code.google.com/p/closure-compiler/source/browse/trunk/README
     *
     * @return string
     */
    public function min($js,$options = array())
    {
		self::_prepare();
        if (! ($tmpFile = tempnam(self::$tempDir, 'minify_'))) {
			throw new Exception('Minify_ClosureCompiler : could not create temp file.');
        }
        file_put_contents($tmpFile, $js);
        exec(self::_getCmd($options, $tmpFile), $output, $result_code);
        
        unlink($tmpFile);
        if ($result_code != 0) {
			//added by pankaj
			if (is_callable($this->_fallbackFunc)) {
				$output[] = "/* Received errors from Closure Compiler API:\n$result_code"
                          . "\n(Using fallback minifier)\n*/\n";
                $output[]= call_user_func($this->_fallbackFunc, $js);
                
            } else {
                throw new Exception('Minify_ClosureCompiler : Closure Compiler execution failed.');
            }
            
        }
        return implode("\n", $output);
    }

    private static function _getCmd($userOptions, $tmpFile)
    {
        $o = array_merge(
            array(
                'charset' => 'utf-8',
                'compilation_level' => 'SIMPLE_OPTIMIZATIONS',
            ),
            $userOptions
        );
        $cmd = self::$javaExecutable . ' -jar ' . escapeshellarg(self::$jarFile)
             . (preg_match('/^[\\da-zA-Z0-9\\-]+$/', $o['charset'])
                ? " --charset {$o['charset']}"
                : '');

        foreach (array('compilation_level') as $opt) {
            if ($o[$opt]) {
                $cmd .= " --{$opt} ". escapeshellarg($o[$opt]);
            }
        }
        return $cmd . ' ' . escapeshellarg($tmpFile);
    }

    private static function _prepare()
    {
        if (! is_file(self::$jarFile)) {
			throw new Exception('Minify_ClosureCompiler : $jarFile('.self::$jarFile.') is not a valid file.');
        }
        if (! is_readable(self::$jarFile)) {
			throw new Exception('Minify_ClosureCompiler : $jarFile('.self::$jarFile.') is not readable.');
        }
        if (! is_dir(self::$tempDir)) {
			throw new Exception('Minify_ClosureCompiler : $tempDir('.self::$tempDir.') is not a valid direcotry.');
        }
        if (! is_writable(self::$tempDir)) {
			throw new Exception('Minify_ClosureCompiler : $tempDir('.self::$tempDir.') is not writable.');
        }
    }
    
  //added by Pankaj  
    /**
     * Default fallback function if CC API fails
     * @param string $js
     * @return string
     */
    protected function _fallback($js)
    {
		return JSMin::minify($js);
    }
}

/* vim:ts=4:sw=4:et */
