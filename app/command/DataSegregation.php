<?php

require_once('include/Utils/Utils.class.php');

use app_controller_Response as Response;
use Illuminate\Database\Capsule\Manager as DB;

class app_command_DataSegregation extends app_command_BaseCommand
{
    public function doSegregation()
    {
        $time_start = microtime(true);
        $clientsWithUsers = app_model_Clients::whereIn('id', [1111])->get();
        $clientsCount = $clientsWithUsers->count();
        $postsCount = 0;

        foreach ($clientsWithUsers as $client) {
            $page = 1;

            while (true) {
                $clientPosts = $client->posts($page);

                $postsCount += $clientPosts->count();

                foreach ($clientPosts as $post) {
                    // Duplicate post
                    $newPost = $post->replicate();
                    $newPost->data_owner_id = $client->id;
                    $newPost->original_post_id = $post->id;
                    $newPost->save();

                    // // Duplicate contact
                    $contact = $post->contact;

                    if ($contact) {
                        $newContact = $contact->replicate();
                        $newContact->post_id = $newPost->id;
                        $newContact->save();
                    }
                    
                    // // Duplicate tags
                    $tags = $post->tags;

                    foreach ($tags as $tag) {
                        $newTag = $tag->replicate();
                        $newTag->save();

                        $newPost->tags()->attach($newTag->id);
                    }

                    // Duplicate notes
                    $notes = $post->notesList;

                    foreach ($notes as $note) {
                        $newNote = $note->replicate();
                        $newNote->post_id = $newPost->id;
                        $newNote->created_at = Utils::getTimestamp();
                        $newNote->save();
                    }

                    // Duplicate post_site entry
                    $postSites = $post->postSites;

                    foreach ($postSites as $postSite) {
                        $newPostSite = $postSite->replicate();
                        $newPostSite->post_id = $newPost->id;
                        $newPostSite->save();
                    }

                    // Change post id on initiative
                    $post->initiatives()->update(['post_id' => $newPost->id]);

                    // Change id on post decision makers
                    $initiatives = $newPost->initiatives;
                    $decisionMakers = $post->decisionMakers;

                    foreach ($decisionMakers as $decisionMaker) {
                        $communication = $decisionMaker->communication;

                        if ($initiatives->contains($communication->post_initiative_id)) {
                            $decisionMaker->post_id = $newPost->id;
                            $decisionMaker->save();
                        }
                    }

                    // Change id on post agency users
                    $agencyUsers = $post->agencyUsers;

                    foreach ($agencyUsers as $agencyUser) {
                        $communication = $agencyUser->communication;

                        if ($initiatives->contains($communication->post_initiative_id)) {
                            $agencyUser->post_id = $newPost->id;
                            $agencyUser->save();
                        }
                    }
                }

                if (!$clientPosts || $clientPosts->count() < 150) {
                    break;
                }

                $page++;
            }
        }

        $time_end = microtime(true);
        $time = 'Data segregation finished in ' . ($time_end - $time_start) . ' seconds. ';
        $updated = $postsCount . ' posts duplicated for ' . $clientsCount . ' client(s)';

        return Response::dump($time . $updated);
    }

    public function duplicate()
    {
        $clientMap = [
            524 => 524,
            904 => 925,
            1088 => 1111,
            1054 => 1076
        ];

        $initiatives = app_model_PostInitiative::hydrateRaw('SELECT i.id, i.post_id, i.initiative_id 
            FROM tbl_post_initiatives i
            JOIN tbl_posts p ON p.id = i.post_id
            WHERE p.data_owner_id IS NULL
            AND i.initiative_id = 1088
        ');

        $total = $initiatives->count();
        $count = 0;

        foreach ($initiatives as $initiative) {
            $post = $initiative->post;

            // Duplicate post
            $newPost = $post->replicate();
            $newPost->data_owner_id = $clientMap[$initiative->initiative_id];
            $newPost->original_post_id = $post->id;
            $newPost->save();

            // Duplicate contact
            $contact = $post->contact;

            if ($contact) {
                $newContact = $contact->replicate();
                $newContact->post_id = $newPost->id;
                $newContact->save();
            }

            // Duplicate tags
            $tags = $post->tags;

            foreach ($tags as $tag) {
                $newTag = $tag->replicate();
                $newTag->save();

                $newPost->tags()->attach($newTag->id);
            }

            // Duplicate notes
            $notes = $post->notesList;

            foreach ($notes as $note) {
                $newNote = $note->replicate();
                $newNote->post_id = $newPost->id;
                $newNote->save();
            }

            // Duplicate post_site entry
            $postSites = $post->postSites;

            foreach ($postSites as $postSite) {
                $newPostSite = $postSite->replicate();
                $newPostSite->post_id = $newPost->id;
                $newPostSite->save();
            }

            // Change post id on initiative
            $initiative->post_id = $newPost->id;
            $initiative->save();

            // Change id on post decision makers
            $decisionMakers = $post->decisionMakers;

            foreach ($decisionMakers as $decisionMaker) {
                $communication = $decisionMaker->communication;

                if ($communication->initiative_id == $initiative->id) {
                    $decisionMaker->post_id = $newPost->id;
                    $decisionMaker->save();
                }
            }

            // Change id on post agency users
            $agencyUsers = $post->agencyUsers;

            foreach ($agencyUsers as $agencyUser) {
                $communication = $agencyUser->communication;

                if ($communication->initiative_id == $initiative->id) {
                    $agencyUser->post_id = $newPost->id;
                    $agencyUser->save();
                }
            }

            var_dump('Done ' . ++$count . ' of ' . $total);
        }

        return Response::dump('Duplication complete.');
    }

    public function revert()
    {
        $posts = app_model_Post::whereNotNull('original_post_id')->get();

        foreach ($posts as $post) {
            $post->initiatives()->update(['post_id' => $post->original_post_id]);
            if ($post->contact) {
                $post->contact->delete();
            }
            $post->tags()->detach();
            $post->notesList()->delete();
            $post->postSites()->delete();
            $post->decisionMakers()->update(['post_id' => $post->original_post_id]);
            $post->agencyUsers()->update(['post_id' => $post->original_post_id]);
            $post->delete();
        }

        return Response::dump('revert finished');
    }
}

?>