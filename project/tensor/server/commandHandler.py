import subprocess

class CommandHandler(object):
    def handle(self, instructions):
        cmd = subprocess.run(instructions)
        code = cmd.returncode
        print(code)
        if (code):
            raise Exception('Command failed')
        else:
            return code