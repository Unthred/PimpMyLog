<?php

class ConfigPHPTest extends TestCase {

    public static function setUpBeforeClass() {
        parent::setUpBeforeClass();

        // Remove existing configuration files before beginning tests
        @unlink( PSKBASE . '/' . CONFIG_FILE_NAME );
        @unlink( PSKBASE . '/' . CONFIG_FILE_NAME_BEFORE_1_5_0 );
        self::unlinkRecursive( PSKBASE . '/' . USER_CONFIGURATION_DIR );
    }

    public static function tearDownAfterClass() {
        parent::tearDownAfterClass();

        // Remove existing configuration files before beginning tests
        @unlink( PSKBASE . '/' . CONFIG_FILE_NAME );
        @unlink( PSKBASE . '/' . CONFIG_FILE_NAME_BEFORE_1_5_0 );
        self::unlinkRecursive( PSKBASE . '/' . USER_CONFIGURATION_DIR );
    }

    public function test_No_Config()
    {
        global $files;
        $this->assertNull( get_config_file_path() );
        $this->assertNull( get_config_file_name() );
        $this->assertNull( get_config_file() );
        list( $badges , $files ) = config_load();
        $this->assertFalse( $files );
        $this->assertCount( 1 , config_check( $files ) );
    }

    public function test_Json_Config_Empty_Files()
    {
        copy( PHPMOCKUP . '/config_nofiles.user.php' , PSKBASE . '/' . CONFIG_FILE_NAME );

        $this->assertStringEndsWith( CONFIG_FILE_NAME , get_config_file_path() );
        $this->assertEquals( CONFIG_FILE_NAME , get_config_file_name() );
        $this->assertArrayHasKey( 'globals', get_config_file() );
        $this->assertArrayHasKey( 'badges', get_config_file() );
        $this->assertArrayNotHasKey( 'files', get_config_file() );
        list( $badges , $files ) = config_load();
        $this->assertFalse( $files );
        $this->assertCount( 1 , config_check( $files ) );
    }

    public function test_Json_Config_With_Files()
    {
        copy( PHPMOCKUP . '/config_3files.user.php' , PSKBASE . '/' . CONFIG_FILE_NAME );
echo "========================================\n";
echo file_get_contents( PHPMOCKUP . '/config_3files.user.php' );

echo "========================================\n";
passthru( 'ls -al _build ' );

        $this->assertStringEndsWith( CONFIG_FILE_NAME , get_config_file_path() );
        $this->assertEquals( CONFIG_FILE_NAME , get_config_file_name() );
        $config = get_config_file();

echo "========================================\n";
print_r($config);
echo "========================================\n";

        $this->assertArrayHasKey( 'globals', $config );
        $this->assertArrayHasKey( 'badges', $config );
        $this->assertArrayHasKey( 'files', $config );
        $this->assertCount( 3 , $config['files'] );

        list( $badges , $files ) = config_load();
        $this->assertInternalType( 'array' , $files );
        $this->assertTrue( config_check( $files ) );
    }

    public function test_Json_Config_Empty_Files_With_Userdir_Files()
    {
        global $files;
        copy( PHPMOCKUP . '/config_nofiles.user.php' , PSKBASE . '/' . CONFIG_FILE_NAME );
        self::unlinkRecursive( PSKBASE . '/' . USER_CONFIGURATION_DIR );
        self::copyDir( PHPMOCKUP . '/config.user.d' , PSKBASE . '/' . USER_CONFIGURATION_DIR );

        $this->assertStringEndsWith( CONFIG_FILE_NAME , get_config_file_path() );
        $this->assertEquals( CONFIG_FILE_NAME , get_config_file_name() );
        $config = get_config_file();
        $this->assertArrayHasKey( 'globals', $config );
        $this->assertArrayHasKey( 'badges', $config );
        $this->assertArrayNotHasKey( 'files', $config );

        list( $badges , $files ) = config_load();
        $this->assertCount( 1 + 1 + 2 + 2 , $files );
        $this->assertTrue( config_check( $files ) );
    }

    public function test_Json_Config_With_Files_With_Userdir_Files()
    {
        global $files;
        copy( PHPMOCKUP . '/config_3files.user.php' , PSKBASE . '/' . CONFIG_FILE_NAME );
        self::unlinkRecursive( PSKBASE . '/' . USER_CONFIGURATION_DIR );
        self::copyDir( PHPMOCKUP . '/config.user.d' , PSKBASE . '/' . USER_CONFIGURATION_DIR );

        $this->assertStringEndsWith( CONFIG_FILE_NAME , get_config_file_path() );
        $this->assertEquals( CONFIG_FILE_NAME , get_config_file_name() );
        $config = get_config_file();

print_r($config);

        $this->assertArrayHasKey( 'globals' , $config );
        $this->assertArrayHasKey( 'badges' , $config );
        $this->assertArrayHasKey( 'files' , $config );

        list( $badges , $files ) = config_load();
        $this->assertCount( 3 + 1 + 1 + 2 + 2 , $files );
        $this->assertTrue( config_check( $files ) );
    }

}
