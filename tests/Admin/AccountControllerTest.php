<?php
/**
 * Created by PhpStorm.
 * User: daniel.zuwala
 * Date: 10/01/2019
 * Time: 09:35
 */

namespace App\Tests\Controller\Admin;

use App\Entity\Account;
use App\Entity\Gender;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class AdminControllerTest extends WebTestCase
{
    /**
     *  Check that regular user don't have access to admin board
     *
     * @dataProvider getUrlsForRegularUsers
     */
    public function testAccessDeniedForRegularUsers(string $httpMethod, string $url)
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'user',
            'PHP_AUTH_PW' => 'user',
        ]);

        $client->request($httpMethod, $url);
        $this->assertSame(Response::HTTP_FORBIDDEN, $client->getResponse()->getStatusCode());
    }

    public function getUrlsForRegularUsers()
    {
        yield ['GET', '/admin/accounts/'];
        yield ['GET', '/admin/accounts/fr/1'];
        yield ['GET', '/admin/account/1/edit'];
        yield ['DELETE', '/admin/account/1/delete'];
    }

    /**
     * Check that admin user has access to admin board
     */
    public function testAdminBackendHomePage()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW' => 'admin',
        ]);

        $crawler = $client->request('GET', '/admin/accounts');
        $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $this->assertGreaterThanOrEqual(
            1,
            $crawler->filter('#accounts-table tbody tr')->count()
        );
    }

    /**
     * Test account creation
     */
    public function testAdminNewAccount()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW' => 'admin',
        ]);

        $lastName = "Dupont";
        $firstName = "Charles";
        $birthday = "1959-03-21";
        $email = "charles@test.org";
        $gender = 'M';
        $country = 'FR';
        $job = '11-1011';

        $crawler = $client->request('GET', '/admin/account/new');
        $form = $crawler->selectButton('Save')->form([
            'account[firstName]' => $firstName,
            'account[lastName]' => $lastName,
            'account[gender][type]' => $gender,
            'account[email]' => $email,
            'account[country]' => $country,
            'account[birthday]' => $birthday,
            'account[job]' => $job,
        ]);
        $client->submit($form);

        $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $account = $client->getContainer()->get('doctrine')->getRepository(Account::class)->findOneBy([
            'email' => $email,
        ]);
        $this->assertNotNull($account);
        $this->assertSame($lastName, $account->getLastName());
        $this->assertSame($firstName, $account->getFirstName());
        $this->assertSame($gender, $account->getGender()->getType());
        $this->assertSame($country, $account->getCountry()->getCode());
        $this->assertSame($birthday, $account->getBirthday()->format('Y-m-d'));
        $this->assertSame($job, $account->getJob()->getCode());
    }

    /**
     * Test account edit
     */
    public function testAdminEditPost()
    {
        $firstName = 'Claude'.mt_rand();

        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW' => 'admin',
        ]);
        $crawler = $client->request('GET', '/admin/account/1/edit');
        $form = $crawler->selectButton('Edit')->form([
            'account[firstName]' => $firstName,
        ]);
        $client->submit($form);

        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());

        $account = $client->getContainer()->get('doctrine')->getRepository(Account::class)->find(1);
        $this->assertSame($firstName, $account->getFirstName());
    }

    /**
     * Test account delete
     */
    public function testAdminDeletePost()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW' => 'admin',
        ]);
        $crawler = $client->request('GET', '/admin/accounts');

        // We select the first delete form and extract the id
        $form = $crawler->selectButton('Delete')->form();
        preg_match('/(\d+)$/', $form->getFormNode()->getAttribute('id'), $match);
        $accountId = $match[0];

        $client->submit($form);

        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());

        $account = $client->getContainer()->get('doctrine')->getRepository(Account::class)->findOneById($accountId);
        $this->assertNull($account);
    }
}
