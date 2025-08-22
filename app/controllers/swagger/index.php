<?php

class swaggerController extends Controller
{
    public function swagger()
    {
        echo <<<EOS

        <!DOCTYPE html>
        <html lang="en">
        <head>
        <meta charset="UTF-8">
        <title>Personnel API Documentation</title>
        <!-- Swagger UI CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swagger-ui-dist@4.19.0/swagger-ui.css">
        <style>
            body {
            margin: 0;
            padding: 0;
            }
            #swagger-ui {
            width: 100%;
            height: 100vh;
            }
        </style>
        </head>
        <body>
        <div id="swagger-ui"></div>

        <!-- Swagger UI JS -->
        <script src="https://cdn.jsdelivr.net/npm/swagger-ui-dist@4.19.0/swagger-ui-bundle.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/swagger-ui-dist@4.19.0/swagger-ui-standalone-preset.js"></script>

        <script>
            window.onload = function() {
            SwaggerUIBundle({
                // Point to your swagger.json file
                url: "swagger.json",
                dom_id: "#swagger-ui",
                deepLinking: true,
                presets: [
                SwaggerUIBundle.presets.apis,
                SwaggerUIStandalonePreset
                ],
                layout: "StandaloneLayout",
                spec: {
                    "openapi": "3.0.3",
                    "info": {
                        "title": "Personnel API",
                        "version": "1.0.0",
                        "description": "API for managing personnel records"
                    },
                    "servers": [
                        { "url": "localhost:8080", "description": "Docker server" },
                         { "url": "localhost", "description": "Local server" }
                    ],
                    "paths": {
                        "/personel/read/q": {
                        "post": {
                            "summary": "Search personnel",
                            "description": "Search personnel with multiple criteria",
                            "requestBody": {
                            "required": true,
                            "content": {
                                "application/json": {
                                "schema": {
                                    "type": "object",
                                    "properties": {
                                    "firstname": { "type": "string" },
                                    "lastname": { "type": "string" },
                                    "email": { "type": "string" }
                                    },
                                    "example": { "firstname": "John", "lastname": "Doe", "email": "john.doe@example.com" }
                                }
                                }
                            }
                            },
                            "responses": {
                            "200": {
                                "description": "Search results",
                                "content": {
                                "application/json": {
                                    "schema": {
                                    "type": "object",
                                    "properties": {
                                        "status": { "type": "boolean" },
                                        "msg": { "type": "string" },
                                        "data": {
                                        "type": "array",
                                        "items": { "\$ref": "#/components/schemas/Personnel" }
                                        }
                                    }
                                    }
                                }
                                }
                            },
                            "400": { "description": "Invalid search criteria" },
                            "500": { "description": "Server error" }
                            }
                        }
                        },
                        "/personel/read": {
                        "get": {
                            "summary": "Get all personnel",
                            "responses": {
                            "200": {
                                "description": "List of all personnel",
                                "content": {
                                "application/json": {
                                    "schema": {
                                    "type": "object",
                                    "properties": {
                                        "status": { "type": "boolean" },
                                        "msg": { "type": "string" },
                                        "data": {
                                        "type": "array",
                                        "items": { "\$ref": "#/components/schemas/Personnel" }
                                        }
                                    }
                                    }
                                }
                                }
                            },
                            "500": { "description": "Server error" }
                            }
                        }
                        },
                        "/personel/read/{id}": {
                        "get": {
                            "summary": "Get personnel by ID",
                            "parameters": [
                            { "name": "id", "in": "path", "required": true, "schema": { "type": "integer" } }
                            ],
                            "responses": {
                            "200": {
                                "description": "Personnel data",
                                "content": {
                                "application/json": {
                                    "schema": {
                                    "type": "object",
                                    "properties": {
                                        "status": { "type": "boolean" },
                                        "msg": { "type": "string" },
                                        "data": { "\$ref": "#/components/schemas/Personnel" }
                                    }
                                    }
                                }
                                }
                            },
                            "404": { "description": "Personnel not found" },
                            "500": { "description": "Server error" }
                            }
                        }
                        },
                        "/personel/create": {
                        "post": {
                            "summary": "Create new personnel",
                            "requestBody": {
                            "required": true,
                            "content": {
                                "application/json": {
                                "schema": { "\$ref": "#/components/schemas/PersonnelInput" }
                                }
                            }
                            },
                            "responses": {
                            "201": {
                                "description": "Personnel created successfully",
                                "content": {
                                "application/json": {
                                    "schema": {
                                    "type": "object",
                                    "properties": {
                                        "status": { "type": "boolean" },
                                        "msg": { "type": "string" },
                                        "data": { "\$ref": "#/components/schemas/Personnel" }
                                    }
                                    }
                                }
                                }
                            },
                            "400": { "description": "Invalid input" },
                            "500": { "description": "Server error" }
                            }
                        }
                        },
                        "/personel/update/{id}": {
                        "patch": {
                            "summary": "Update personnel",
                            "parameters": [
                            { "name": "id", "in": "path", "required": true, "schema": { "type": "integer" } }
                            ],
                            "requestBody": {
                            "required": true,
                            "content": { "application/json": { "schema": { "\$ref": "#/components/schemas/PersonnelInput" } } }
                            },
                            "responses": {
                            "200": {
                                "description": "Personnel updated successfully",
                                "content": {
                                "application/json": {
                                    "schema": {
                                    "type": "object",
                                    "properties": {
                                        "status": { "type": "boolean" },
                                        "msg": { "type": "string" },
                                        "data": { "\$ref": "#/components/schemas/Personnel" }
                                    }
                                    }
                                }
                                }
                            },
                            "404": { "description": "Personnel not found" },
                            "400": { "description": "Invalid input" },
                            "500": { "description": "Server error" }
                            }
                        }
                        },
                        "/personel/delete/{id}": {
                        "delete": {
                            "summary": "Delete personnel",
                            "parameters": [
                            { "name": "id", "in": "path", "required": true, "schema": { "type": "integer" } }
                            ],
                            "responses": {
                            "200": {
                                "description": "Personnel deleted successfully",
                                "content": {
                                "application/json": {
                                    "schema": {
                                    "type": "object",
                                    "properties": {
                                        "status": { "type": "boolean" },
                                        "msg": { "type": "string" }
                                    }
                                    }
                                }
                                }
                            },
                            "404": { "description": "Personnel not found" },
                            "500": { "description": "Server error" }
                            }
                        }
                        }
                    },
                    "components": {
                        "schemas": {
                        "Personnel": {
                            "type": "object",
                            "properties": {
                            "id": { "type": "integer" },
                            "firstname": { "type": "string" },
                            "lastname": { "type": "string" },
                            "email": { "type": "string" },
                            "phonenumber": { "type": "string" },
                            "birthday": { "type": "string", "format": "date" }
                            },
                            "required": ["id","firstname","lastname","email","phonenumber","birthday"]
                        },
                        "PersonnelInput": {
                            "type": "object",
                            "properties": {
                            "firstname": { "type": "string" },
                            "lastname": { "type": "string" },
                            "email": { "type": "string" },
                            "phonenumber": { "type": "string" },
                            "birthday": { "type": "string", "format": "date" }
                            },
                            "required": ["firstname","lastname","email","phonenumber","birthday"]
                        }
                        }
                    }
                    }

            });
            };
        </script>
        </body>
        </html>

        EOS;
    }
}
