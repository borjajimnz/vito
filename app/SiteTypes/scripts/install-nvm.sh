#!/bin/bash

# Function to check if NVM is installed
check_nvm_installed() {
    if [ -d "$HOME/.nvm" ]; then
        echo "NVM is already installed."
        return 0
    else
        echo "NVM is not installed."
        return 1
    fi
}

# Check if NVM is installed
if check_nvm_installed; then
    echo "NVM is already installed. Skipping installation."
else
    # Download and install NVM
    echo "Installing NVM..."
    curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.3/install.sh | bash
fi

# Load NVM
export NVM_DIR="$HOME/.nvm"
[ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh"
[ -s "$NVM_DIR/bash_completion" ] && \. "$NVM_DIR/bash_completion"

# Install the specified Node version
nvm install __node_version__

echo "Installation completed."
