<?php

namespace Core\App\Enum;

enum ConstraintEnum: string
{
    case DELETE_RESTRICT   = 'ON DELETE RESTRICT';      // Prevent deletion if referenced
    case DELETE_CASCADE    = 'ON DELETE CASCADE';       // Delete dependent rows automatically
    case DELETE_SETNULL    = 'ON DELETE SET NULL';      // Set foreign key to NULL on delete
    case DELETE_NOACTION   = 'ON DELETE NO ACTION';     // Standard SQL default
    case DELETE_SETDEFAULT = 'ON DELETE SET DEFAULT';   // Set foreign key to its default value on delete
    
    case UPDATE_RESTRICT   = 'ON UPDATE RESTRICT';      // Prevent update if referenced
    case UPDATE_CASCADE    = 'ON UPDATE CASCADE';       // Update dependent rows automatically
    case UPDATE_SETNULL    = 'ON UPDATE SET NULL';      // Set foreign key to NULL on update
    case UPDATE_NOACTION   = 'ON UPDATE NO ACTION';     // Standard SQL default
    case UPDATE_SETDEFAULT = 'ON UPDATE SET DEFAULT';   // Set foreign key to its default value on update
}