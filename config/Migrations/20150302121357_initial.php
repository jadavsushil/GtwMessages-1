<?php
use Phinx\Migration\AbstractMigration;

class Initial extends AbstractMigration {

    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     *
     * @return void
     */
    public function change()
    {

		$table = $this->table('messages');
    $table
      ->addColumn('id', 'integer', [
        'limit' => '11', 
        'unsigned' => '', 
        'null' => '', 
        'default' => '', 
      ])
      ->addColumn('user_id', 'integer', [
        'limit' => '11', 
        'unsigned' => '', 
        'null' => '', 
        'default' => '', 
      ])
      ->addColumn('recipient_id', 'integer', [
        'limit' => '11', 
        'unsigned' => '', 
        'null' => '', 
        'default' => '', 
      ])
      ->addColumn('title', 'string', [
        'limit' => '50', 
        'null' => '', 
        'default' => '', 
      ])
      ->addColumn('body', 'text', [
        'limit' => '', 
        'null' => '', 
        'default' => '', 
      ])
      ->addColumn('is_read', 'integer', [
        'limit' => '4', 
        'unsigned' => '', 
        'null' => '', 
        'default' => '0', 
      ])
      ->addColumn('read_on_date', 'datetime', [
        'limit' => '', 
        'null' => '', 
        'default' => '', 
      ])
      ->addColumn('response_to_id', 'integer', [
        'limit' => '11', 
        'unsigned' => '', 
        'null' => '', 
        'default' => '', 
      ])
      ->addColumn('created', 'datetime', [
        'limit' => '', 
        'null' => '', 
        'default' => '', 
      ])
      ->addColumn('modified', 'datetime', [
        'limit' => '', 
        'null' => '', 
        'default' => '', 
      ])
      ->save();

		$table = $this->table('sent_messages');
    $table
      ->addColumn('id', 'integer', [
        'limit' => '11', 
        'unsigned' => '', 
        'null' => '', 
        'default' => '', 
      ])
      ->addColumn('user_id', 'integer', [
        'limit' => '11', 
        'unsigned' => '', 
        'null' => '', 
        'default' => '', 
      ])
      ->addColumn('recipient_id', 'integer', [
        'limit' => '11', 
        'unsigned' => '', 
        'null' => '', 
        'default' => '', 
      ])
      ->addColumn('title', 'string', [
        'limit' => '50', 
        'null' => '', 
        'default' => '', 
      ])
      ->addColumn('body', 'text', [
        'limit' => '', 
        'null' => '', 
        'default' => '', 
      ])
      ->addColumn('is_read', 'integer', [
        'limit' => '4', 
        'unsigned' => '', 
        'null' => '', 
        'default' => '0', 
      ])
      ->addColumn('read_on_date', 'datetime', [
        'limit' => '', 
        'null' => '', 
        'default' => '', 
      ])
      ->addColumn('response_to_id', 'integer', [
        'limit' => '11', 
        'unsigned' => '', 
        'null' => '', 
        'default' => '', 
      ])
      ->addColumn('created', 'datetime', [
        'limit' => '', 
        'null' => '', 
        'default' => '', 
      ])
      ->addColumn('modified', 'datetime', [
        'limit' => '', 
        'null' => '', 
        'default' => '', 
      ])
      ->save();

		$table = $this->table('trash_messages');
    $table
      ->addColumn('id', 'integer', [
        'limit' => '11', 
        'unsigned' => '', 
        'null' => '', 
        'default' => '', 
      ])
      ->addColumn('message_id', 'integer', [
        'limit' => '11', 
        'unsigned' => '', 
        'null' => '', 
        'default' => '', 
      ])
      ->addColumn('user_id', 'integer', [
        'limit' => '11', 
        'unsigned' => '', 
        'null' => '', 
        'default' => '', 
      ])
      ->addColumn('recipient_id', 'integer', [
        'limit' => '11', 
        'unsigned' => '', 
        'null' => '', 
        'default' => '', 
      ])
      ->addColumn('title', 'string', [
        'limit' => '50', 
        'null' => '', 
        'default' => '', 
      ])
      ->addColumn('body', 'text', [
        'limit' => '', 
        'null' => '', 
        'default' => '', 
      ])
      ->addColumn('is_read', 'integer', [
        'limit' => '4', 
        'unsigned' => '', 
        'null' => '', 
        'default' => '0', 
      ])
      ->addColumn('read_on_date', 'datetime', [
        'limit' => '', 
        'null' => '', 
        'default' => '', 
      ])
      ->addColumn('response_to_id', 'integer', [
        'limit' => '11', 
        'unsigned' => '', 
        'null' => '', 
        'default' => '', 
      ])
      ->addColumn('deleted_by', 'string', [
        'limit' => '30', 
        'null' => '', 
        'default' => '', 
      ])
      ->addColumn('created', 'datetime', [
        'limit' => '', 
        'null' => '', 
        'default' => '', 
      ])
      ->addColumn('modified', 'datetime', [
        'limit' => '', 
        'null' => '', 
        'default' => '', 
      ])
      ->save();
    }

    /**
     * Migrate Up.
     *
     * @return void
     */
    public function up()
    {
    }

    /**
     * Migrate Down.
     *
     * @return void
     */
    public function down()
    {
    }

}
