<?php

use PHPUnit\Framework\TestCase;


class UserServiceTest extends TestCase
{
    private UserService $userService;
    private UserRepository $userRepository;

    protected function setUp():void
    {
        $conn = Database::getConnection();
        $this->userRepository = new UserRepository($conn);
        $this->userService = new UserService($this->userRepository);
       
        $this->userRepository->deleteAll();
    }

    public function testLoginNotFound()
    {
        $this->expectException(ValidationException::class);

        $request = new UserLoginRequest();
        $request->email = "raih@coom";
        $request->password = "1234";

        $this->userService->login($request);
    }

    public function testLoginWrongPassword()
    {
        $user = new User();
        $user->email = "raih@com";
        $user->name = "raihan";
        $user->password = password_hash("1234", PASSWORD_BCRYPT);

        $this->expectException(ValidationException::class);

        $request = new UserLoginRequest();
        $request->email = "raih@com";
        $request->password = "salah";

        $this->userService->login($request);
    }

    public function testLoginSuccess()
    {
        $user = new User();
        $user->email = "raih@com";
        $user->name = "1234";
        $user->password = password_hash("1234", PASSWORD_BCRYPT);

        $this->expectException(ValidationException::class);

        $request = new UserLoginRequest();
        $request->email = "raih@com";
        $request->password = "1234";

        $response = $this->userService->login($request);

        self::assertEquals($request->email, $response->user->email);
        self::assertTrue(password_verify($request->password, $response->user->password));
    }


}




?>