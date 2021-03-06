<?php

/**
 * Test class for Skaya_Model_Collection_Abstract.
 * Generated by PHPUnit on 2011-05-21 at 13:35:48.
 */
class CollectionTest extends PHPUnit_Framework_TestCase {
	/**
	 * @var Skaya_Model_Collection_Abstract
	 */
	protected $collection;

	protected $_items = array(
		array(
			'id' => 1,
			'name' => 'test1',
			'text' => 'test text 1'
		),
		array(
			'id' => 2,
			'name' => 'test2',
			'text' => 'test text 2'
		),
		array(
			'id' => 3,
			'name' => 'test3',
			'text' => 'test text 3'
		),
		array(
			'id' => 4,
			'name' => 'test4',
			'text' => 'test text 4'
		)
	);

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp() {
		$this->collection = new TestCollection($this->_items);
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown() {
	}

	public function testCount() {
		$this->assertEquals(count($this->_items), count($this->collection));
	}
	
	public function testOffsetExists() {
		for ($i=0; $i < count($this->_items); $i++) {
			$this->assertTrue(isset($this->collection[$i]));
		}
		$this->assertFalse(isset($this->collection[count($this->_items) + 1]));
	}

	/**
	 * @todo Implement testOffsetGet().
	 */
	public function testOffsetGet() {
		for ($i=0; $i < count($this->_items); $i++) {
			$this->assertInstanceOf('TestModel', $this->collection[$i]);
			$this->assertEquals($this->_items[$i], $this->collection[$i]->toArray());
		}
	}

	/**
	 * @todo Implement testOffsetSet().
	 */
	public function testOffsetSet() {
		$object = new TestModel(array(
			'id' => 10,
			'name' => 'random test',
			'text' => 'random text'
		));
		$this->collection[0] = $object;
		$this->assertEquals($object, $this->collection[0]);
	}

	/**
	 * @todo Implement testOffsetUnset().
	 */
	public function testOffsetUnset() {
		$startCount = count($this->collection);
		unset($this->collection[0]);
		$this->assertFalse(isset($this->collection[0]));
		$this->assertEquals($startCount - 1, count($this->collection));
	}

	/**
	 * @todo Implement testGetIterator().
	 */
	public function testGetIterator() {
		$this->assertInstanceOf('Iterator', $this->collection->getIterator());
		$i = 0;
		foreach ($this->collection as $index => $item) {
			$this->assertInstanceOf('testModel', $item);
			$this->assertEquals($i, $index);
			$this->assertEquals($this->_items[$i++], $item->toArray());
		}
	}

	/**
	 * @todo Implement testClear().
	 */
	public function testClear() {
		$this->collection->clear();
		$this->assertEquals(0, count($this->collection));
	}

	/**
	 * @todo Implement testToArray().
	 */
	public function testToArray() {
		$array = $this->collection->toArray();
		$this->assertInternalType('array', $array);
		$this->assertEquals($this->_items, $array);
	}

	/**
	 * @expectedException Skaya_Model_Collection_Exception
	 * @return void
	 */
	public function testWrongItemsException() {
		$object = new TestCollection(array(1));
	}

	/**
	 * @expectedException Skaya_Model_Collection_Exception
	 * @return void
	 */
	public function testWrongItemTypeException() {
		$object = new DeadCollection($this->_items);
	}
}

class TestCollection extends Skaya_Model_Collection_Abstract {

	protected $_itemType = 'TestModel';

}

class DeadCollection extends Skaya_Model_Collection_Abstract {

	protected $_itemType = 'UnexistentClass';

}