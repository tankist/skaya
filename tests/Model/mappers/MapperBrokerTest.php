<?php
//require_once APPLICATION_PATH . '/models/mappers/MapperBroker.php';

/**
 * Test class for Skaya_Model_Mapper_MapperBroker.
 * Generated by PHPUnit on 2011-02-17 at 15:15:17.
 */
class Skaya_Model_Mapper_MapperBrokerTest extends PHPUnit_Framework_TestCase {
	/**
	 * @var Skaya_Model_Mapper_MapperBroker
	 */
	protected $mappers;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp() {
		parent::setUp();
		Skaya_Model_Mapper_MapperBroker::setDefaultProvider('db');
		$this->mappers = Skaya_Model_Mapper_MapperBroker::getInstance();
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown() {
		$this->mappers->resetMappers();
		Skaya_Model_Mapper_MapperBroker::getPluginLoader()->clearPaths();
	}

	/**
	 * @todo Implement testSetDefaultProvider().
	 */
	public function testGetSetDefaultProvider() {
		$testProviderType = 'db';
		Skaya_Model_Mapper_MapperBroker::setDefaultProvider($testProviderType);
		$this->assertEquals($testProviderType, Skaya_Model_Mapper_MapperBroker::getDefaultProvider());
	}

	/**
	 * @todo Implement testSetPluginLoader().
	 */
	public function testGetSetPluginLoader() {
		//Test empty initial plugin loader
		$pluginLoader = Skaya_Model_Mapper_MapperBroker::getPluginLoader();
		$this->assertInstanceOf('Zend_Loader_PluginLoader', $pluginLoader);

		$pluginLoader = new Zend_Loader_PluginLoader();
		Skaya_Model_Mapper_MapperBroker::setPluginLoader($pluginLoader);
		$this->assertEquals($pluginLoader, Skaya_Model_Mapper_MapperBroker::getPluginLoader());
	}

	/**
	 * @todo Implement testAddPath().
	 */
	public function testAddPath() {
		Skaya_Model_Mapper_MapperBroker::addPath(
			realpath(TESTS_PATH . '/Model/mappers/_files/mappers'),
			'MyApp'
		);
		$mapper = $this->mappers->test('db');
		$this->assertEquals('MyApp_Db_Test', $mapper->getTestResponse());
	}

	public function testAddPrefix() {
		//Save current include_path to change it to new one
		$oldIncludePath = get_include_path();
		set_include_path(dirname(__FILE__) . DIRECTORY_SEPARATOR . '_files' . PATH_SEPARATOR . $oldIncludePath);
		Skaya_Model_Mapper_MapperBroker::addPrefix('MyApp_Mapper');
		$mapper = $this->mappers->testAddPrefix('db');
		$this->assertEquals('MyApp_Mapper_Db_TestAddPrefix', $mapper->getTestResponse());
		//Restore saved include path
		set_include_path($oldIncludePath);
	}

	/**
	 * @todo Implement testAddMapper().
	 */
	public function testAddMapper() {
		require_once dirname(__FILE__) . DIRECTORY_SEPARATOR .
		             join(DIRECTORY_SEPARATOR, array('_files', 'MyApp', 'Mapper', 'Db', 'TestAddPrefix.php'));
		/**
		 * @var Skaya_Model_Mapper_Abstract $mapper
		 */
		$mapper = new MyApp_Mapper_Db_TestAddPrefix();
		$this->mappers->addMapper($mapper);
		$stack = Skaya_Model_Mapper_MapperBroker::getStack($mapper->getProvider());
		$this->assertTrue(isset($stack[$mapper->getName()]));
		$this->assertEquals($mapper, $this->mappers->getMapper($mapper->getName(), $mapper->getProvider()));
	}

	/**
	 * @todo Implement testResetMappers().
	 */
	public function testResetMappers() {
		Skaya_Model_Mapper_MapperBroker::addPath(
			dirname(__FILE__) . DIRECTORY_SEPARATOR . '_files' . DIRECTORY_SEPARATOR . 'MyApp' . DIRECTORY_SEPARATOR . 'Mapper',
			'MyApp_Mapper'
		);

		$dbMapper = $this->mappers->testAddPrefix('db');
		$sessionMapper = $this->mappers->testSessionMapper('session');

		$dbStack = Skaya_Model_Mapper_MapperBroker::getStack('db');
		$this->assertEquals(1, count($dbStack));
		$sessionStack = Skaya_Model_Mapper_MapperBroker::getStack('session');
		$this->assertEquals(1, count($sessionStack));

		$this->mappers->resetMappers('db');
		$dbStack = Skaya_Model_Mapper_MapperBroker::getStack('db');
		$this->assertEquals(0, count($dbStack));
		$this->assertEquals(1, count($sessionStack));

		$dbMapper = $this->mappers->testAddPrefix('db');

		$dbStack = Skaya_Model_Mapper_MapperBroker::getStack('db');
		$this->assertEquals(1, count($dbStack));
		$sessionStack = Skaya_Model_Mapper_MapperBroker::getStack('session');
		$this->assertEquals(1, count($sessionStack));

		$this->mappers->resetMappers();

		$dbStack = Skaya_Model_Mapper_MapperBroker::getStack('db');
		$this->assertEquals(0, count($dbStack));
		$sessionStack = Skaya_Model_Mapper_MapperBroker::getStack('session');
		$this->assertEquals(0, count($sessionStack));
	}

	/**
	 * @todo Implement testGetStaticMapper().
	 */
	public function testGetStaticMapper() {
		Skaya_Model_Mapper_MapperBroker::addPath(
			dirname(__FILE__) . DIRECTORY_SEPARATOR . '_files' . DIRECTORY_SEPARATOR . 'MyApp' . DIRECTORY_SEPARATOR . 'Mapper',
			'MyApp_Mapper'
		);

		$mapper = Skaya_Model_Mapper_MapperBroker::getStaticMapper('testAddPrefix');
		$this->assertInstanceOf('MyApp_Mapper_Db_TestAddPrefix', $mapper);

		$mapper = Skaya_Model_Mapper_MapperBroker::getStaticMapper('testSessionMapper', 'session');
		$this->assertInstanceOf('MyApp_Mapper_Session_TestSessionMapper', $mapper);
	}

	/**
	 * @expectedException Skaya_Model_Mapper_Exception
	 */
	public function testGetExistingMapper() {
		Skaya_Model_Mapper_MapperBroker::addPath(
			dirname(__FILE__) . DIRECTORY_SEPARATOR . '_files' . DIRECTORY_SEPARATOR . 'MyApp' . DIRECTORY_SEPARATOR . 'Mapper',
			'MyApp_Mapper'
		);

		$dbMapper = $this->mappers->testAddPrefix('db');
		$this->assertEquals($dbMapper, $this->mappers->getExistingMapper('testAddPrefix', 'db'));
		$sessionMapper = $this->mappers->getExistingMapper('testSessionMapper', 'session');
	}

	/**
	 * @todo Implement testGetExistingMappers().
	 */
	public function testGetExistingMappers() {
		Skaya_Model_Mapper_MapperBroker::addPath(
			dirname(__FILE__) . DIRECTORY_SEPARATOR . '_files' . DIRECTORY_SEPARATOR . 'MyApp' . DIRECTORY_SEPARATOR . 'Mapper',
			'MyApp_Mapper'
		);

		$dbMapper = $this->mappers->testAddPrefix('db');
		$sessionMapper = $this->mappers->testSessionMapper('session');

		$mappers = Skaya_Model_Mapper_MapperBroker::getExistingMappers();
		$this->assertEquals(1, count($mappers));

		$mappers = Skaya_Model_Mapper_MapperBroker::getExistingMappers('db');
		$this->assertEquals(1, count($mappers));

		$mappers = Skaya_Model_Mapper_MapperBroker::getExistingMappers('session');
		$this->assertEquals(1, count($mappers));
	}

	/**
	 * @todo Implement testHasMapper().
	 */
	public function testHasMapper() {
		Skaya_Model_Mapper_MapperBroker::addPath(
			dirname(__FILE__) . DIRECTORY_SEPARATOR . '_files' . DIRECTORY_SEPARATOR . 'MyApp' . DIRECTORY_SEPARATOR . 'Mapper',
			'MyApp_Mapper'
		);

		$dbMapper = $this->mappers->testAddPrefix('db');
		$sessionMapper = $this->mappers->testSessionMapper('session');
		$this->assertTrue(Skaya_Model_Mapper_MapperBroker::hasMapper('testAddPrefix', 'db'));
		$this->assertTrue(Skaya_Model_Mapper_MapperBroker::hasMapper('testAddPrefix'));
		$this->assertTrue(Skaya_Model_Mapper_MapperBroker::hasMapper('testSessionMapper', 'session'));
		$this->assertFalse(Skaya_Model_Mapper_MapperBroker::hasMapper('testSessionMapper'));
	}

	/**
	 * @todo Implement testRemoveMapper().
	 */
	public function testRemoveMapper() {
		Skaya_Model_Mapper_MapperBroker::addPath(
			dirname(__FILE__) . DIRECTORY_SEPARATOR . '_files' . DIRECTORY_SEPARATOR . 'MyApp' . DIRECTORY_SEPARATOR . 'Mapper',
			'MyApp_Mapper'
		);

		$dbMapper = $this->mappers->testAddPrefix('db');
		$sessionMapper = $this->mappers->testSessionMapper('session');

		$this->assertFalse(Skaya_Model_Mapper_MapperBroker::removeMapper('testSessionMapper'));
		$this->assertTrue(Skaya_Model_Mapper_MapperBroker::removeMapper('testSessionMapper', 'session'));
		$this->assertTrue(Skaya_Model_Mapper_MapperBroker::removeMapper('testAddPrefix'));
	}

	/**
	 * @todo Implement testGetStack().
	 */
	public function testGetStack() {
		$defaultStack = Skaya_Model_Mapper_MapperBroker::getStack();
		$dbStack = Skaya_Model_Mapper_MapperBroker::getStack('db');
		$this->assertEquals($dbStack, $defaultStack);

		$dbMapper = $this->mappers->testAddPrefix('db');
		$newStack = Skaya_Model_Mapper_MapperBroker::getStack('test');
		$this->assertInstanceOf('Skaya_Model_Mapper_MapperBroker_PriorityStack', $newStack);
		$this->assertNotEquals($defaultStack, $newStack);
	}

	/**
	 * @todo Implement testGetInstance().
	 */
	public function testGetInstance() {
		$broker = Skaya_Model_Mapper_MapperBroker::getInstance();
		$this->assertInstanceOf('Skaya_Model_Mapper_MapperBroker', $broker);
		$this->assertEquals($this->mappers, $broker);
	}

	/**
	 * @todo Implement testGetMapper().
	 */
	public function testGetMapper() {
		Skaya_Model_Mapper_MapperBroker::addPath(
			dirname(__FILE__) . DIRECTORY_SEPARATOR . '_files' . DIRECTORY_SEPARATOR . 'MyApp' . DIRECTORY_SEPARATOR . 'Mapper',
			'MyApp_Mapper'
		);

		$mapper = $this->mappers->getMapper('testAddPrefix');
		$this->assertInstanceOf('MyApp_Mapper_Db_TestAddPrefix', $mapper);

		$mapper = $this->mappers->getMapper('testSessionMapper', 'session');
		$this->assertInstanceOf('MyApp_Mapper_Session_TestSessionMapper', $mapper);
	}

	/**
	 * @todo Implement testCall().
	 */
	public function testCall() {
		Skaya_Model_Mapper_MapperBroker::addPath(
			dirname(__FILE__) . DIRECTORY_SEPARATOR . '_files' . DIRECTORY_SEPARATOR . 'MyApp' . DIRECTORY_SEPARATOR . 'Mapper',
			'MyApp_Mapper'
		);

		$mapper = $this->mappers->testAddPrefix();
		$this->assertInstanceOf('MyApp_Mapper_Db_TestAddPrefix', $mapper);

		$mapper = $this->mappers->testSessionMapper('session');
		$this->assertInstanceOf('MyApp_Mapper_Session_TestSessionMapper', $mapper);
	}

	/**
	 * @todo Implement testGet().
	 */
	public function testGet() {
		Skaya_Model_Mapper_MapperBroker::addPath(
			dirname(__FILE__) . DIRECTORY_SEPARATOR . '_files' . DIRECTORY_SEPARATOR . 'MyApp' . DIRECTORY_SEPARATOR . 'Mapper',
			'MyApp_Mapper'
		);

		$mapper = $this->mappers->testAddPrefix;
		$this->assertInstanceOf('MyApp_Mapper_Db_TestAddPrefix', $mapper);
	}

	public function testNormalizeMapperName() {
		Skaya_Model_Mapper_MapperBroker::addPath(
			dirname(__FILE__) . DIRECTORY_SEPARATOR . '_files' . DIRECTORY_SEPARATOR . 'MyApp' . DIRECTORY_SEPARATOR . 'Mapper',
			'MyApp_Mapper'
		);

		$mapper = $this->mappers->getMapper('Test_Add_Prefix', 'db');
		$this->assertInstanceOf('MyApp_Mapper_Db_TestAddPrefix', $mapper);
	}

	/**
	 * @expectedException Skaya_Model_Mapper_Exception
	 */
	public function testSetPluginLoaderException() {
		Skaya_Model_Mapper_MapperBroker::setPluginLoader(new stdClass());
	}

	/**
	 * @expectedException Skaya_Model_Mapper_Exception
	 */
	public function testNotFoundMapperException() {
		$mapper = $this->mappers->testNotExistendMapper;
	}

	/**
	 * @expectedException Skaya_Model_Mapper_Exception
	 */
	public function testFakeMapperLoader() {
		Skaya_Model_Mapper_MapperBroker::addPath(
			dirname(__FILE__) . DIRECTORY_SEPARATOR . '_files' . DIRECTORY_SEPARATOR . 'MyApp' . DIRECTORY_SEPARATOR . 'Mapper',
			'MyApp_Mapper'
		);

		$mapper = $this->mappers->fakeMapper('db');
	}
}

?>
