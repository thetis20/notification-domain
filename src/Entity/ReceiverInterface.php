<?php

namespace Notification\Domain\Entity;

interface ReceiverInterface{
    
    public function getId(): ?string;
    public function getPhone(): ?string;
    public function getEmail(): ?string;
}