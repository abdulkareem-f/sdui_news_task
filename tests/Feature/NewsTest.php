<?php

namespace Tests\Feature;

use App\Models\News;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class NewsTest extends TestCase
{
    public User $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user, 'sanctum');
    }

    public function test_create_news()
    {
        $news = [
            'title'     =>  'Test news title',
            'content'   =>  'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.'
        ];
        $response = $this->postJson('api/news', $news);
        $response->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json->hasAll(['data', 'msg'])
                    ->where('data.title', 'Test news title')
                    ->where('data.content', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.')
                    ->where('data.user_id', $this->user->id)
            );
    }

    public function test_create_new_validation()
    {
        $news = [
            'content'   =>  'Test short content'
        ];
        $response = $this->postJson('api/news', $news);
        $response->assertStatus(422)  // missing title, content is short
            ->assertJsonValidationErrorFor('title', 'errors')
            ->assertJson(fn (AssertableJson $json) =>
                $json->hasAll(['msg', 'errors'])
                    ->where('errors.title.0', 'The title field is required.')
                    ->where('errors.content.0', 'The content must be at least 50 characters.')
            );
    }

    public function test_read_news()
    {
        $randomNews = News::inRandomOrder()->first();
        $response = $this->getJson("api/news/{$randomNews->id}");
        $response->assertStatus(200)
            ->assertJsonStructure(
                [
                    'data' => $this->newsObjStructure,
                ]
            );
    }

    public function test_not_found_news()
    {
        $maxNewsId = News::max('id') + 1;
        $response = $this->getJson("api/news/$maxNewsId");
        $response->assertStatus(404);
    }

    public function test_update_news()
    {
        $randomNews = News::inRandomOrder()->first();
        $newsUpdate = [
            'title'     =>  'Updated - Test news title',
            'content'   =>  'Updated - Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.'
        ];
        $response = $this->patchJson("api/news/{$randomNews->id}", $newsUpdate);
        $response->assertStatus(200)
            ->assertJsonPath('data.id', $randomNews['id'])
            ->assertJsonPath('data.title', $newsUpdate['title'])
            ->assertJsonPath('data.content', $newsUpdate['content'])
            ->assertJsonPath('data.user_id', $randomNews['user_id']);
    }

    public function test_delete_news()
    {
        $randomNews = News::inRandomOrder()->first();
        $response = $this->deleteJson("api/news/{$randomNews->id}");
        $response->assertStatus(200)
            ->assertExactJson(
                [
                    'msg'   =>  'News has been deleted successfully'
                ]
            );
        $this->assertDatabaseMissing('news', $randomNews->toArray());
    }

    public function test_paginate_news()
    {
        $response = $this->getJson('api/news');
        $response->assertStatus(200)
            ->assertJsonStructure(
                [
                    'current_page',
                    'data' => [
                        '*' => $this->newsObjStructure
                    ],
                    'first_page_url',
                    'from',
                    'last_page',
                    'last_page_url',
                    'links' => [
                        '*' => ['url', 'label', 'active']
                    ],
                    'next_page_url',
                    'path',
                    'per_page',
                    'prev_page_url',
                    'to',
                    'total'
                ]
            );
    }
}
