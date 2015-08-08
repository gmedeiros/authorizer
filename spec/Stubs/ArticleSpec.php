<?php

namespace spec\Deefour\Authorizer\Stubs;

use Deefour\Authorizer\Stubs\User;
use Deefour\Authorizer\Stubs\ArticlePolicy;
use Deefour\Authorizer\Stubs\ArticleScope;
use Deefour\Authorizer\Stubs\FeaturedArticlePolicy;
use Deefour\Authorizer\Stubs\FeaturedArticleScope;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ArticleSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Deefour\Authorizer\Stubs\Article');
    }

    function it_should_resolve_a_policy()
    {
        $this->policy(new User)->shouldReturnAnInstanceOf(ArticlePolicy::class);
    }

    function it_should_resolve_a_custom_policy()
    {
        $this->policy(new User, FeaturedArticlePolicy::class)->shouldReturnAnInstanceOf(FeaturedArticlePolicy::class);
    }

    function it_should_resolve_a_scope()
    {
        $this->scope(new User)->shouldReturnAnInstanceOf(ArticleScope::class);
    }

    function it_should_resolve_a_custom_scope()
    {
        $this->scope(new User, FeaturedArticleScope::class)->shouldReturnAnInstanceOf(FeaturedArticleScope::class);
    }
}
