<?php

namespace Uspdev\Replicado\Tests;

use PHPUnit\Framework\TestCase;
use Uspdev\Replicado\Pessoa;
use Uspdev\Replicado\DB;
use Faker\Factory;

class PessoaTest extends TestCase
{
    public function test_nomeCompleto(){
        # Limpando Tabela
        DB::getInstance()->prepare('DELETE FROM PESSOA')->execute();

        $sql = "INSERT INTO PESSOA (codpes, nompes, nompesttd) VALUES 
                                   (convert(int,:codpes),:nompes,:nompesttd)";

        $data = [
            'codpes' => 123456,
            'nompes' => 'Fulano da Silva',
            'nompesttd' => 'Fulana da Silva',
        ];
        DB::getInstance()->prepare($sql)->execute($data);
        $this->assertSame('Fulana da Silva',Pessoa::nomeCompleto(123456));
    }

    public function test_obterCodpesPorEmail(){
        DB::getInstance()->prepare('DELETE FROM EMAILPESSOA')->execute();

        $sql = "INSERT INTO EMAILPESSOA (codpes, codema) VALUES 
                                   (convert(int,:codpes),:codema)";

        $data = [
            'codpes' => 123456,
            'codema' => 'fulana@usp.br'
        ];
        DB::getInstance()->prepare($sql)->execute($data);
        $this->assertSame('123456',Pessoa::obterCodpesPorEmail('fulana@usp.br'));
    }

    public function test_verificarEstagioUSP(){
        DB::getInstance()->prepare('DELETE FROM LOCALIZAPESSOA')->execute();

        $sql = "INSERT INTO LOCALIZAPESSOA (codpes, tipvin) VALUES 
                                   (convert(int,:codpes),:tipvin)";

        $data = [
            'codpes' => 123456,
            'tipvin' => 'ESTAGIARIORH'
        ];
        DB::getInstance()->prepare($sql)->execute($data);
        $this->assertTrue(Pessoa::verificarEstagioUSP(123456));
    }

    public function test_email(){
        DB::getInstance()->prepare('DELETE FROM EMAILPESSOA')->execute();

        $sql = "INSERT INTO EMAILPESSOA (codpes, stamtr, codema) VALUES 
                                   (convert(int,:codpes),:stamtr,:codema)";

        $data = [
            'codpes' => 123456,
            'stamtr' => 'S',
            'codema' => 'fulana@usp.br'
        ];
        DB::getInstance()->prepare($sql)->execute($data);
        $this->assertSame('fulana@usp.br',Pessoa::email('123456'));
    }

    public function test_emailusp(){
        DB::getInstance()->prepare('DELETE FROM EMAILPESSOA')->execute();

        $sql = "INSERT INTO EMAILPESSOA (codpes, stausp, codema) VALUES 
                                   (convert(int,:codpes),:stausp,:codema)";

        $data = [
            'codpes' => 123456,
            'stausp' => 'S',
            'codema' => 'fulana@usp.br'
        ];
        DB::getInstance()->prepare($sql)->execute($data);
        $this->assertSame('fulana@usp.br',Pessoa::emailusp('123456'));
    }

    public function test_emails(){
        DB::getInstance()->prepare('DELETE FROM EMAILPESSOA')->execute();

        $sql = "INSERT INTO EMAILPESSOA (codpes, codema) VALUES 
                                   (convert(int,:codpes),:codema)";

        $data = [
            'codpes' => 123456,
            'codema' => 'fulana@usp.br'
        ];
        DB::getInstance()->prepare($sql)->execute($data);
        $output = Pessoa::emails(123456);
        $this->assertIsArray($output);
        $this->assertSame('fulana@usp.br',$output[0]);
    }

    public function test_telefones(){
        DB::getInstance()->prepare('DELETE FROM TELEFPESSOA')->execute();

        $sql = "INSERT INTO TELEFPESSOA (codpes, codddd, numtel) VALUES 
                                   (convert(int,:codpes),:codddd,:numtel)";

        $data = [
            'codpes' => 123456,
            'codddd' => 11,
            'numtel' => 954668532 
        ];
        DB::getInstance()->prepare($sql)->execute($data);
        $this->assertSame('(11) 954668532',Pessoa::telefones(123456)[0]);
    }

    public function test_obterRamalUsp(){
        DB::getInstance()->prepare('DELETE FROM LOCALIZAPESSOA')->execute();

        $sql = "INSERT INTO LOCALIZAPESSOA (codpes, numtelfmt) VALUES 
                                   (convert(int,:codpes),:numtelfmt)";

        $data = [
            'codpes' => 123456,
            'numtelfmt' => 954668532 
        ];
        DB::getInstance()->prepare($sql)->execute($data);
        $this->assertSame('954668532',Pessoa::obterRamalUsp(123456));
    }
    
    public function test_vinculos(){
        DB::getInstance()->prepare('DELETE FROM LOCALIZAPESSOA')->execute();

        $sql = "INSERT INTO LOCALIZAPESSOA (codpes, codundclg, tipvinext, nomfnc, nomset, sglclgund) VALUES 
                                   (convert(int,:codpes), convert(int,:codundclg),:tipvinext,:nomfnc,:nomset,:sglclgund)";

        $data = [
            'codpes' => 123456,
            'codundclg' => 8,
            'tipvinext' => 'Estagiário',
            'nomfnc' => 'Estagiário',
            'nomset' => 'Diretoria Faculdade de Filosofia, Letras e Ciências Humanas',
            'sglclgund' => 'CG',
        ];
        DB::getInstance()->prepare($sql)->execute($data);
        $this->assertSame("Estagiário - Estagiário - Diretoria Faculdade de Filosofia, Letras e Ciências Humanas - CG",Pessoa::vinculos(123456)[0]);
    }

    public function test_vinculosSiglas(){
        DB::getInstance()->prepare('DELETE FROM LOCALIZAPESSOA')->execute();

        $sql = "INSERT INTO LOCALIZAPESSOA (codpes, sitatl, codundclg, tipvin) VALUES 
                                   (convert(int,:codpes),:sitatl,convert(int,:codundclg),:tipvin)";

        $data = [
            'codpes' => 123456,
            'sitatl' => 'A',
            'codundclg' => 8,
            'tipvin' => 'ALUNOGR',
        ];
        DB::getInstance()->prepare($sql)->execute($data);
        $this->assertSame('ALUNOGR',Pessoa::vinculosSiglas(123456,8)[0]);
    }

    public function test_setoresSiglas(){
        DB::getInstance()->prepare('DELETE FROM LOCALIZAPESSOA')->execute();

        $sql = "INSERT INTO LOCALIZAPESSOA (codpes, sitatl, codundclg, nomabvset) VALUES 
                                   (convert(int,:codpes),:sitatl,convert(int,:codundclg),:nomabvset)";

        $data = [
            'codpes' => 123456,
            'sitatl' => 'A',
            'codundclg' => 8,
            'nomabvset' => 'FFLCH',
        ];
        DB::getInstance()->prepare($sql)->execute($data);
        $this->assertSame('FFLCH',Pessoa::setoresSiglas(123456,8)[0]);
    }

    public function test_totalVinculo(){
        DB::getInstance()->prepare('DELETE FROM LOCALIZAPESSOA')->execute();

        $sql = "INSERT INTO LOCALIZAPESSOA (codpes, tipvinext, codundclg, sitatl) VALUES 
                                   (convert(int,:codpes),:tipvinext,convert(int,:codundclg),:sitatl)";                         

        $data = [
            'codpes' => 123456,
            'tipvinext' => 'Servidor',
            'codundclg' => 8,
            'sitatl' => 'A'
        ];
        DB::getInstance()->prepare($sql)->execute($data);
        $this->assertSame('1',Pessoa::totalVinculo('Servidor', 8));
    }
}