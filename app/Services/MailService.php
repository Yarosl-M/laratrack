<?php

namespace App\Services;

use App\Models\Ticket;
use App\Models\User;
use BeyondCode\Mailbox\InboundEmail;
use Illuminate\Support\Str;

class MailService {
    public function handleEmail(array $emailContents) {
        /**
         * address
         * subject
         * content
         * no attachments for you
         */
        // also that means you'd need to create a temporary username and then change it later when user actually registers
        
        if (!(
            array_key_exists('email', $emailContents) && array_key_exists('subject', $emailContents)
            && array_key_exists('content', $emailContents)
        )) return null;

        $user = User::where('email', $emailContents['email'])->first();
        if (!is_null($user)) {
            if (!is_null($user->deactivated_at)) return null;

            // just create a ticket as usual
            // return $ticket;
        }
        // new user
        $user = new User;
        $user->email = $emailContents['email'];
        $user->username = '_user_' . strtolower(Str::ulid()->toBase32());
        $user->password = '';
        // actually temp models could probably have been a good idea
    }
}