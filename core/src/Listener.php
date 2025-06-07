<?php

declare(strict_types=1);

namespace MXRVX\Telegram\Bot;

use Longman\TelegramBot\Entities\CallbackQuery;
use Longman\TelegramBot\Entities\ChatJoinRequest;
use Longman\TelegramBot\Entities\ChatMemberUpdated;
use Longman\TelegramBot\Entities\ChosenInlineResult;
use Longman\TelegramBot\Entities\InlineQuery;
use Longman\TelegramBot\Entities\Message;
use Longman\TelegramBot\Entities\Payments\PreCheckoutQuery;
use Longman\TelegramBot\Entities\Payments\ShippingQuery;
use Longman\TelegramBot\Entities\Poll;
use Longman\TelegramBot\Entities\PollAnswer;
use Longman\TelegramBot\Entities\Update;

/**
 * Class Listener
 *
 * Base class for commands. It includes some helper methods that can fetch data directly from the Update object.
 *
 * @method Message             getMessage()            Optional. New incoming message of any kind — text, photo, sticker, etc.
 * @method Message             getEditedMessage()      Optional. New version of a message that is known to the bot and was edited
 * @method Message             getChannelPost()        Optional. New post in the channel, can be any kind — text, photo, sticker, etc.
 * @method Message             getEditedChannelPost()  Optional. New version of a post in the channel that is known to the bot and was edited
 * @method InlineQuery         getInlineQuery()        Optional. New incoming inline query
 * @method ChosenInlineResult  getChosenInlineResult() Optional. The result of an inline query that was chosen by a user and sent to their chat partner.
 * @method CallbackQuery       getCallbackQuery()      Optional. New incoming callback query
 * @method ShippingQuery       getShippingQuery()      Optional. New incoming shipping query. Only for invoices with flexible price
 * @method PreCheckoutQuery    getPreCheckoutQuery()   Optional. New incoming pre-checkout query. Contains full information about checkout
 * @method Poll                getPoll()               Optional. New poll state. Bots receive only updates about polls, which are sent or stopped by the bot
 * @method PollAnswer          getPollAnswer()         Optional. A user changed their answer in a non-anonymous poll. Bots receive new votes only in polls that were sent by the bot itself.
 * @method ChatMemberUpdated   getMyChatMember()       Optional. The bot's chat member status was updated in a chat. For private chats, this update is received only when the bot is blocked or unblocked by the user.
 * @method ChatMemberUpdated   getChatMember()         Optional. A chat member's status was updated in a chat. The bot must be an administrator in the chat and must explicitly specify “chat_member” in the list of allowed_updates to receive these updates.
 * @method ChatJoinRequest     getChatJoinRequest()    Optional. A request to join the chat has been sent. The bot must have the can_invite_users administrator right in the chat to receive these updates.
 */
abstract class Listener implements ListenerInterface
{
    public function __construct(protected App $app, protected Update $update) {}

    /**
     * Relay any non-existing function calls to Update object.
     *
     * This is purely a helper method to make requests from within execute() method easier.
     *
     * @return mixed
     */
    public function __call(string $name, array $arguments)
    {
        return \call_user_func_array([$this->update, $name], $arguments);
    }
}
