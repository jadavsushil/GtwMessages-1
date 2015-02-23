<?php if ($this->Session->read('Auth.User')): ?>
    <li id="<?php echo $this->fetch('users_li_id'); ?>" class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <span class='link-extendedo'><?php echo __('Message') ?> <b class="caret"></b></span>
        </a>
        <ul class="dropdown-menu">
            <li>
                <?php
                echo $this->Html->link('Compose', array(
                    'plugin' => 'gtw_messages',
                    'controller' => 'messages',
                    'action' => 'compose',
                ))
                ?>
            </li>
            <li>
                <?php
                echo $this->Html->link('Inbox', array(
                    'plugin' => 'gtw_messages',
                    'controller' => 'messages',
                    'action' => 'index',
                ))
                ?>
            </li>
            <li>
                <?php
                echo $this->Html->link('Sent Box', array(
                    'plugin' => 'gtw_messages',
                    'controller' => 'messages',
                    'action' => 'index',
                    'sent'
                ))
                ?>
            </li>
            <li>
    <?php
    echo $this->Html->link('Trash', array(
        'plugin' => 'gtw_messages',
        'controller' => 'messages',
        'action' => 'index',
        'trash'
    ));
    ?>
            </li>
        </ul>
    </li>
<?php endif; ?>
