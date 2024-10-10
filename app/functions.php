<?php

function falconLogger()
{
    return FALCON_CONTAINER->get('logger');
}

function falconDB()
{
    return FALCON_CONTAINER->get('db');
}

function falconTemplate()
{
    return FALCON_CONTAINER->get('template');
}

function falconEmail()
{
    return FALCON_CONTAINER->get('email');
}
