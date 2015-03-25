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

		$table = $this->table('message_read_statuses');
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
      ->addColumn('thread_participant_id', 'integer', [
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
      ->addColumn('status', 'integer', [
        'limit' => '11', 
        'unsigned' => '', 
        'null' => '', 
        'default' => '0', 
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
      ->addColumn('thread_id', 'integer', [
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
      ->addColumn('parent_id', 'integer', [
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
      ->save();

		$table = $this->table('thread_participants');
    $table
      ->addColumn('id', 'integer', [
        'limit' => '11', 
        'unsigned' => '', 
        'null' => '', 
        'default' => '', 
      ])
      ->addColumn('thread_id', 'integer', [
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
      ->addColumn('created', 'datetime', [
        'limit' => '', 
        'null' => '', 
        'default' => '', 
      ])
      ->save();

		$table = $this->table('threads');
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
      ->addColumn('thread_participant_count', 'integer', [
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
