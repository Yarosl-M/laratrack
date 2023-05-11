<?php

namespace App\Models;

use App\Enums\ActionType;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/* Actions in ticket (e. g. tag updates, closing/reopening, assigning ticket to an operator etc.) */
class ThreadAction extends Model
{
    protected $fillable = ['ticket_id', 'user_id', 'attributes'];
    public $incrementing = false;
    use HasUlids;
    use HasFactory;

    public function scopeOfType($query, ActionType $type) {
        return $query->where('attributes->type', $type->value);
    }

    public function attributes(): Attribute {
        return Attribute::make(
            get: function(string $json) {
                $attr = json_decode($json, true);
                switch ($attr['type']) {
                    case 'tags_changed':
                        $tags = [];
                        foreach ($attr['new_tags'] as $id) {
                            $tags[] = Tag::find($id);
                        }
                        $attr['new_tags'] = $tags;
                        break;
                    case 'priority_changed':
                        $new = Priority::find($attr['new_priority']);
                        $attr['new_priority'] = $new;
                        break;
                    case 'ticket_assigned':
                        $assignee = User::find($attr['assignee']);
                        $attr['assignee'] = $assignee;
                        break;
                    case 'ticket_unassigned':
                    case 'ticket_closed':
                    case 'ticket_reopened':
                    case 'feedback_sent':
                    case 'ticket_archived':
                    default:
                        break;
                }
                return $attr;
            }
        );
    }

    public function ticket(): BelongsTo {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }
    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id');
    }
}
