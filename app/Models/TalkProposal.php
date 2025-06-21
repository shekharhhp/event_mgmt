<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TalkProposal extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'speaker_id', 'presentation_pdf', 'status'
    ];

    public function speaker()
    {
        return $this->belongsTo(User::class, 'speaker_id');
    }

    public function revisions()
    {
        return $this->hasMany(TalkProposalRevision::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
